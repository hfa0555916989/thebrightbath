<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TurnstileTest extends TestCase
{
    use RefreshDatabase;

    /** Cloudflare's verdict; null simulates an outage (HTTP 500). */
    private ?bool $cloudflareSays = false;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Http::fake(['challenges.cloudflare.com/*' => fn () => $this->cloudflareSays === null
            ? Http::response(null, 500)
            : Http::response(['success' => $this->cloudflareSays])]);
    }

    private function enable(): void
    {
        config(['services.turnstile.site_key' => 'site-key', 'services.turnstile.secret_key' => 'secret-key']);
    }

    private function registration(array $overrides = []): array
    {
        return array_merge([
            'name' => 'عميل تجريبي',
            'email' => 'client@example.com',
            'phone' => '0500000000',
            'password' => 'Secret-pass-123',
            'password_confirmation' => 'Secret-pass-123',
            'terms' => '1',
        ], $overrides);
    }

    public static function formPages(): array
    {
        return [
            'register' => ['register'],
            'login' => ['login'],
            'forgot password' => ['password.forgot'],
        ];
    }

    // ── Off by default ───────────────────────────────────────────────────

    /** @dataProvider formPages */
    public function test_widget_is_hidden_without_keys(string $route): void
    {
        $this->get(route($route))->assertOk()->assertDontSee('cf-turnstile', false);
    }

    public function test_without_keys_registration_and_login_work_without_any_check(): void
    {
        $this->post(route('register'), $this->registration())->assertSessionHasNoErrors();
        $this->assertTrue(User::where('email', 'client@example.com')->exists());

        $user = User::factory()->create(['password' => 'Secret-pass-123']);
        $this->post(route('login'), ['email' => $user->email, 'password' => 'Secret-pass-123'])->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);

        Http::assertNothingSent();
    }

    // ── On with keys ─────────────────────────────────────────────────────

    /** @dataProvider formPages */
    public function test_widget_is_shown_with_keys(string $route): void
    {
        $this->enable();

        $this->get(route($route))->assertOk()->assertSee('data-sitekey="site-key"', false);
    }

    public function test_registration_needs_a_passed_challenge(): void
    {
        $this->enable();

        $this->post(route('register'), $this->registration())->assertSessionHasErrors('cf-turnstile-response');
        $this->post(route('register'), $this->registration(['cf-turnstile-response' => 'bot']))->assertSessionHasErrors('cf-turnstile-response');
        $this->assertFalse(User::where('email', 'client@example.com')->exists());

        $this->cloudflareSays = true;
        $this->post(route('register'), $this->registration(['cf-turnstile-response' => 'human']))->assertSessionHasNoErrors();
        $this->assertTrue(User::where('email', 'client@example.com')->exists());

        Http::assertSent(fn ($request) => $request['secret'] === 'secret-key' && $request['response'] === 'human');
    }

    public function test_login_needs_a_passed_challenge(): void
    {
        $this->enable();
        $user = User::factory()->create(['password' => 'Secret-pass-123']);

        $this->post(route('login'), ['email' => $user->email, 'password' => 'Secret-pass-123', 'cf-turnstile-response' => 'bot'])
            ->assertSessionHasErrors('cf-turnstile-response');
        $this->assertGuest();

        $this->cloudflareSays = true;
        $this->post(route('login'), ['email' => $user->email, 'password' => 'Secret-pass-123', 'cf-turnstile-response' => 'human']);
        $this->assertAuthenticatedAs($user);
    }

    public function test_forgot_password_needs_a_passed_challenge(): void
    {
        $this->enable();
        $user = User::factory()->create();

        $this->post(route('password.email'), ['email' => $user->email])->assertSessionHasErrors('cf-turnstile-response');
        $this->assertNull($user->fresh()->password_reset_token);

        $this->cloudflareSays = true;
        $this->post(route('password.email'), ['email' => $user->email, 'cf-turnstile-response' => 'human'])->assertSessionHasNoErrors();
        $this->assertNotNull($user->fresh()->password_reset_token);
    }

    public function test_cloudflare_outage_fails_closed(): void
    {
        $this->enable();
        $this->cloudflareSays = null;

        $this->post(route('register'), $this->registration(['cf-turnstile-response' => 'human']))
            ->assertSessionHasErrors('cf-turnstile-response');
    }
}
