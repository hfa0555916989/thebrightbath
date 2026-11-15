<?php

namespace Tests\Feature;

use App\Mail\LoginCodeMail;
use App\Models\LoginCode;
use App\Models\User;
use App\Services\Auth\LoginCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginCodeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        RateLimiter::clear('login-code');
    }

    /** Request a code and return the one that was mailed. */
    private function requestCode(string $email): string
    {
        $this->post(route('login.code'), ['email' => $email])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login.code.verify'));

        $code = null;
        Mail::assertQueued(LoginCodeMail::class, function (LoginCodeMail $mail) use ($email, &$code) {
            $code = $mail->code;

            return $mail->hasTo(strtolower($email));
        });

        return $code;
    }

    public function test_login_page_offers_the_email_code_option(): void
    {
        $this->get(route('login'))->assertOk()->assertSee(route('login.code'), false);
        $this->get(route('register'))->assertOk()->assertSee(route('login.code'), false);
        $this->get(route('login.code'))->assertOk()->assertSee('أرسل لي الرمز');
    }

    public function test_code_is_mailed_and_only_its_hash_is_stored(): void
    {
        $code = $this->requestCode('client@example.com');

        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
        $record = LoginCode::where('email', 'client@example.com')->sole();
        $this->assertNotSame($code, $record->code_hash);
        $this->assertTrue(now()->addMinutes(LoginCodeService::TTL_MINUTES + 1)->gt($record->expires_at));
    }

    public function test_correct_code_signs_in_an_existing_client(): void
    {
        $user = User::factory()->create(['email' => 'client@example.com', 'role' => 'client']);
        $code = $this->requestCode('Client@Example.com');

        $this->get(route('login.code.verify'))->assertOk()->assertSee('client@example.com');
        $this->post(route('login.code.verify'), ['code' => $code])->assertRedirect(route('client.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_unknown_email_gets_a_new_verified_client_account(): void
    {
        $code = $this->requestCode('new.person@example.com');
        $this->post(route('login.code.verify'), ['code' => $code]);

        $user = User::where('email', 'new.person@example.com')->sole();
        $this->assertSame('client', $user->role);
        $this->assertNotNull($user->email_verified_at);
        $this->assertSame('New Person', $user->name);
        $this->assertAuthenticatedAs($user);
    }

    public function test_code_verifies_an_unverified_account(): void
    {
        $user = User::factory()->create([
            'email' => 'client@example.com', 'role' => 'client',
            'email_verified_at' => null, 'verification_token' => 'abc',
        ]);

        $code = $this->requestCode('client@example.com');
        $this->post(route('login.code.verify'), ['code' => $code]);

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertNull($user->fresh()->verification_token);
    }

    public function test_admin_and_counselor_land_on_their_dashboards(): void
    {
        User::factory()->create(['email' => 'admin@example.com', 'role' => 'admin']);
        $code = $this->requestCode('admin@example.com');
        $this->post(route('login.code.verify'), ['code' => $code])->assertRedirect(route('admin.dashboard'));

        auth()->logout();
        Mail::fake();
        User::factory()->create(['email' => 'counselor@example.com', 'role' => 'counselor']);
        $code = $this->requestCode('counselor@example.com');
        $this->post(route('login.code.verify'), ['code' => $code])->assertRedirect(route('consultant.dashboard'));
    }

    public function test_wrong_code_fails_and_too_many_tries_burn_the_code(): void
    {
        // The per-code attempt cap is under test here, not the per-IP throttle.
        $this->withoutMiddleware(ThrottleRequests::class);
        $code = $this->requestCode('client@example.com');
        $wrong = $code === '000000' ? '111111' : '000000';

        for ($i = 0; $i < LoginCodeService::MAX_ATTEMPTS; $i++) {
            $this->post(route('login.code.verify'), ['code' => $wrong])->assertSessionHasErrors('code');
        }

        // Even the right code no longer works.
        $this->post(route('login.code.verify'), ['code' => $code])->assertSessionHasErrors('code');
        $this->assertGuest();
    }

    public function test_code_works_once(): void
    {
        $code = $this->requestCode('client@example.com');
        $this->post(route('login.code.verify'), ['code' => $code]);
        $this->assertAuthenticated();

        auth()->logout();
        $this->withSession(['login_code_email' => 'client@example.com'])
            ->post(route('login.code.verify'), ['code' => $code])->assertSessionHasErrors('code');
        $this->assertGuest();
    }

    public function test_expired_code_is_rejected(): void
    {
        $code = $this->requestCode('client@example.com');
        $this->travel(LoginCodeService::TTL_MINUTES + 1)->minutes();

        $this->post(route('login.code.verify'), ['code' => $code])->assertSessionHasErrors('code');
        $this->assertGuest();
    }

    public function test_only_the_newest_code_is_valid(): void
    {
        $first = $this->requestCode('client@example.com');
        Mail::fake();
        $second = $this->requestCode('client@example.com');

        if ($first !== $second) {
            $this->post(route('login.code.verify'), ['code' => $first])->assertSessionHasErrors('code');
        }
        $this->post(route('login.code.verify'), ['code' => $second])->assertSessionHasNoErrors();
        $this->assertAuthenticated();
    }

    public function test_verify_page_without_a_requested_code_goes_back_to_the_email_step(): void
    {
        $this->get(route('login.code.verify'))->assertRedirect(route('login.code'));
        $this->post(route('login.code.verify'), ['code' => '123456'])->assertRedirect(route('login.code'));
    }

    public function test_sending_is_rate_limited_per_email(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post(route('login.code'), ['email' => 'client@example.com'])->assertRedirect();
        }

        $this->post(route('login.code'), ['email' => 'client@example.com'])->assertStatus(429);
    }

    public function test_invalid_email_is_rejected(): void
    {
        $this->post(route('login.code'), ['email' => 'not-an-email'])->assertSessionHasErrors('email');
        Mail::assertNothingQueued();
    }

    public function test_turnstile_protects_sending_when_enabled(): void
    {
        config(['services.turnstile.site_key' => 'site-key', 'services.turnstile.secret_key' => 'secret-key']);
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => false])]);

        $this->get(route('login.code'))->assertSee('cf-turnstile', false);
        $this->post(route('login.code'), ['email' => 'client@example.com', 'cf-turnstile-response' => 'bad'])
            ->assertSessionHasErrors('cf-turnstile-response');
        Mail::assertNothingQueued();
    }
}
