<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Auth\AppleLogin;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use phpseclib4\Crypt\EC;
use phpseclib4\Crypt\RSA;
use Tests\TestCase;

class SocialLoginTest extends TestCase
{
    use RefreshDatabase;

    /** RSA key that stands in for Apple's ID-token signing key. */
    private string $appleSigningKey;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    // ── Hidden until configured ──────────────────────────────────────────

    public static function socialRoutes(): array
    {
        return [
            'google redirect' => ['get', 'login.google'],
            'google callback' => ['get', 'login.google.callback'],
            'apple redirect' => ['get', 'login.apple'],
            'apple callback' => ['post', 'login.apple.callback'],
        ];
    }

    #[DataProvider('socialRoutes')]
    public function test_routes_are_404_without_keys(string $method, string $route): void
    {
        $this->{$method}(route($route))->assertNotFound();
    }

    public function test_buttons_are_hidden_without_keys(): void
    {
        $this->get(route('login'))->assertOk()
            ->assertDontSee(route('login.google'), false)
            ->assertDontSee(route('login.apple'), false);
    }

    public function test_buttons_show_once_keys_are_set(): void
    {
        $this->enableGoogle();
        $this->enableApple();

        foreach (['login', 'register'] as $page) {
            $this->get(route($page))->assertOk()
                ->assertSee(route('login.google'), false)
                ->assertSee(route('login.apple'), false);
        }
    }

    // ── Google ───────────────────────────────────────────────────────────

    private function enableGoogle(): void
    {
        config(['services.google.client_id' => 'google-client', 'services.google.client_secret' => 'google-secret']);
    }

    private function fakeGoogleUser(string $id, ?string $email, bool $verified = true, string $name = 'Sara Ali'): void
    {
        $user = (new SocialiteUser)->setRaw(['email_verified' => $verified])->map([
            'id' => $id, 'name' => $name, 'email' => $email,
        ]);
        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->andReturn($user);
        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    }

    public function test_google_redirect_goes_to_google_with_our_callback(): void
    {
        $this->enableGoogle();

        $location = $this->get(route('login.google'))->assertRedirect()->headers->get('Location');

        $this->assertStringStartsWith('https://accounts.google.com/', $location);
        $this->assertStringContainsString('client_id=google-client', $location);
        $this->assertStringContainsString(urlencode(route('login.google.callback')), $location);
    }

    public function test_google_creates_a_verified_client_account(): void
    {
        $this->enableGoogle();
        $this->fakeGoogleUser('g-123', 'Sara@Example.com');

        $this->get(route('login.google.callback'))->assertRedirect(route('client.dashboard'));

        $user = User::where('email', 'sara@example.com')->sole();
        $this->assertSame('g-123', $user->google_id);
        $this->assertSame('Sara Ali', $user->name);
        $this->assertSame('client', $user->role);
        $this->assertNotNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);
    }

    public function test_google_links_an_existing_account_by_email_and_keeps_its_role(): void
    {
        $this->enableGoogle();
        $admin = User::factory()->create(['email' => 'boss@example.com', 'role' => 'admin', 'name' => 'المدير']);
        $this->fakeGoogleUser('g-777', 'boss@example.com');

        $this->get(route('login.google.callback'))->assertRedirect(route('admin.dashboard'));

        $this->assertSame('g-777', $admin->fresh()->google_id);
        $this->assertSame('المدير', $admin->fresh()->name);
        $this->assertSame(1, User::count());
        $this->assertAuthenticatedAs($admin);
    }

    public function test_google_finds_the_account_by_google_id_even_if_the_email_changed(): void
    {
        $this->enableGoogle();
        $user = User::factory()->create(['email' => 'old@example.com', 'google_id' => 'g-1']);
        $this->fakeGoogleUser('g-1', 'new@example.com');

        $this->get(route('login.google.callback'));

        $this->assertAuthenticatedAs($user);
        $this->assertSame(1, User::count());
    }

    public function test_google_account_without_a_verified_email_is_refused(): void
    {
        $this->enableGoogle();
        $this->fakeGoogleUser('g-9', 'someone@example.com', verified: false);

        $this->get(route('login.google.callback'))->assertRedirect(route('login'))->assertSessionHas('error');

        $this->assertGuest();
        $this->assertSame(0, User::count());
    }

    public function test_google_failure_returns_to_login_with_a_message(): void
    {
        $this->enableGoogle();
        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->andThrow(new \RuntimeException('invalid state'));
        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $this->get(route('login.google.callback'))->assertRedirect(route('login'))->assertSessionHas('error');
        $this->assertGuest();
    }

    // ── Apple ────────────────────────────────────────────────────────────

    private function enableApple(): void
    {
        // Generated with phpseclib (a Socialite dependency): openssl_pkey_new()
        // needs an openssl.cnf that Windows PHP builds often lack.
        $p8 = EC::createKey('secp256r1')->toString('PKCS8');

        config([
            'services.apple.client_id' => 'com.thebrightbath.web',
            'services.apple.team_id' => 'TEAM123456',
            'services.apple.key_id' => 'KEY1234567',
            // Stored on one line with literal \n, as in a hosting panel
            'services.apple.private_key' => str_replace("\n", '\n', $p8),
        ]);
    }

    /**
     * Fake Apple's token and keys endpoints; the ID token carries $claims.
     */
    private function fakeApple(array $claims): void
    {
        $this->appleSigningKey = RSA::createKey(2048)->toString('PKCS8');
        $details = openssl_pkey_get_details(openssl_pkey_get_private($this->appleSigningKey))['rsa'];
        $b64 = fn (string $v) => rtrim(strtr(base64_encode($v), '+/', '-_'), '=');

        $idToken = JWT::encode(array_merge([
            'iss' => 'https://appleid.apple.com',
            'aud' => 'com.thebrightbath.web',
            'iat' => time(),
            'exp' => time() + 600,
        ], $claims), $this->appleSigningKey, 'RS256', 'apple-kid');

        Http::fake([
            'appleid.apple.com/auth/token' => Http::response(['id_token' => $idToken]),
            'appleid.apple.com/auth/keys' => Http::response(['keys' => [[
                'kty' => 'RSA', 'kid' => 'apple-kid', 'use' => 'sig', 'alg' => 'RS256',
                'n' => $b64($details['n']), 'e' => $b64($details['e']),
            ]]]),
        ]);
    }

    /** A state as our redirect creates it, carrying $nonce. */
    private function state(string $nonce, ?int $time = null): string
    {
        return Crypt::encryptString(json_encode(['n' => $nonce, 't' => $time ?? time()]));
    }

    public function test_apple_redirect_carries_client_id_state_and_hashed_nonce(): void
    {
        $this->enableApple();

        $location = $this->get(route('login.apple'))->assertRedirect()->headers->get('Location');
        parse_str(parse_url($location, PHP_URL_QUERY), $query);

        $this->assertStringStartsWith('https://appleid.apple.com/auth/authorize', $location);
        $this->assertSame('com.thebrightbath.web', $query['client_id']);
        $this->assertSame(route('login.apple.callback'), $query['redirect_uri']);
        $this->assertSame('form_post', $query['response_mode']);
        $nonce = app(AppleLogin::class)->nonceFrom($query['state']);
        $this->assertNotNull($nonce);
        $this->assertSame(hash('sha256', $nonce), $query['nonce']);
    }

    public function test_apple_client_secret_is_an_es256_jwt_from_the_p8_key(): void
    {
        $this->enableApple();

        [$header, $payload] = array_map(
            fn ($part) => json_decode(base64_decode(strtr($part, '-_', '+/')), true),
            array_slice(explode('.', app(AppleLogin::class)->clientSecret()), 0, 2),
        );

        $this->assertSame('ES256', $header['alg']);
        $this->assertSame('KEY1234567', $header['kid']);
        $this->assertSame('TEAM123456', $payload['iss']);
        $this->assertSame('com.thebrightbath.web', $payload['sub']);
    }

    public function test_apple_creates_an_account_with_the_name_from_the_first_sign_in(): void
    {
        $this->enableApple();
        $this->fakeApple(['sub' => 'apple-001', 'email' => 'Noor@Example.com', 'email_verified' => 'true', 'nonce' => hash('sha256', 'n-1')]);

        $this->post(route('login.apple.callback'), [
            'code' => 'auth-code',
            'state' => $this->state('n-1'),
            'user' => json_encode(['name' => ['firstName' => 'نور', 'lastName' => 'أحمد']]),
        ])->assertRedirect(route('client.dashboard'));

        $user = User::where('email', 'noor@example.com')->sole();
        $this->assertSame('apple-001', $user->apple_id);
        $this->assertSame('نور أحمد', $user->name);
        $this->assertAuthenticatedAs($user);

        Http::assertSent(fn ($request) => $request->url() === 'https://appleid.apple.com/auth/token'
            && $request['code'] === 'auth-code'
            && $request['client_id'] === 'com.thebrightbath.web');
    }

    public function test_apple_signs_in_a_linked_account_by_apple_id_without_an_email(): void
    {
        $this->enableApple();
        $user = User::factory()->create(['apple_id' => 'apple-002']);
        $this->fakeApple(['sub' => 'apple-002', 'nonce' => hash('sha256', 'n-2')]);

        $this->post(route('login.apple.callback'), ['code' => 'c', 'state' => $this->state('n-2')]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_apple_token_with_a_different_nonce_is_refused(): void
    {
        $this->enableApple();
        $this->fakeApple(['sub' => 'apple-003', 'email' => 'x@example.com', 'email_verified' => true, 'nonce' => hash('sha256', 'other')]);

        $this->post(route('login.apple.callback'), ['code' => 'c', 'state' => $this->state('n-3')])
            ->assertRedirect(route('login'))->assertSessionHas('error');

        $this->assertGuest();
        $this->assertSame(0, User::count());
    }

    public function test_apple_token_for_another_app_is_refused(): void
    {
        $this->enableApple();
        $this->fakeApple(['sub' => 'apple-004', 'aud' => 'com.someone.else', 'email' => 'x@example.com', 'email_verified' => true, 'nonce' => hash('sha256', 'n-4')]);

        $this->post(route('login.apple.callback'), ['code' => 'c', 'state' => $this->state('n-4')]);

        $this->assertGuest();
    }

    public function test_apple_old_or_forged_state_is_refused(): void
    {
        $this->enableApple();
        $this->fakeApple(['sub' => 'apple-005', 'email' => 'x@example.com', 'email_verified' => true, 'nonce' => hash('sha256', 'n-5')]);

        $this->post(route('login.apple.callback'), ['code' => 'c', 'state' => $this->state('n-5', time() - 3600)])
            ->assertRedirect(route('login'));
        $this->post(route('login.apple.callback'), ['code' => 'c', 'state' => 'forged'])
            ->assertRedirect(route('login'));

        $this->assertGuest();
        Http::assertNothingSent();
    }

    public function test_apple_without_a_verified_email_for_a_new_account_is_refused(): void
    {
        $this->enableApple();
        $this->fakeApple(['sub' => 'apple-006', 'nonce' => hash('sha256', 'n-6')]);

        $this->post(route('login.apple.callback'), ['code' => 'c', 'state' => $this->state('n-6')])
            ->assertRedirect(route('login'))->assertSessionHas('error');

        $this->assertGuest();
    }

    public function test_apple_refusing_the_token_exchange_is_logged_with_its_reason(): void
    {
        $this->enableApple();
        Http::fake(['appleid.apple.com/auth/token' => Http::response(['error' => 'invalid_client'], 400)]);
        Log::spy();

        $this->post(route('login.apple.callback'), ['code' => 'c', 'state' => $this->state('n-7')])
            ->assertRedirect(route('login'))->assertSessionHas('error');

        Log::shouldHaveReceived('warning')->with('Apple sign-in: token exchange refused', ['status' => 400, 'error' => 'invalid_client']);
        $this->assertGuest();
    }

    public function test_apple_malformed_private_key_is_logged_instead_of_crashing(): void
    {
        $this->enableApple();
        config(['services.apple.private_key' => 'not-a-key']);
        Http::fake();
        Log::spy();

        $this->post(route('login.apple.callback'), ['code' => 'c', 'state' => $this->state('n-8')])
            ->assertRedirect(route('login'))->assertSessionHas('error');

        Log::shouldHaveReceived('warning')->with('Apple sign-in: token request failed', Mockery::type('array'));
        Http::assertNothingSent();
    }

    public function test_apple_rejected_id_token_is_logged_with_its_reason(): void
    {
        $this->enableApple();
        $this->fakeApple(['sub' => 'apple-009', 'nonce' => hash('sha256', 'other')]);
        Log::spy();

        $this->post(route('login.apple.callback'), ['code' => 'c', 'state' => $this->state('n-9')]);

        Log::shouldHaveReceived('warning')->with('Apple sign-in: ID token rejected', ['reason' => 'nonce']);
        $this->assertGuest();
    }
}
