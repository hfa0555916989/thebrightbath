<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationRecaptchaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Http::fake(['www.google.com/recaptcha/*' => Http::response(['success' => false])]);
    }

    private function form(array $overrides = []): array
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

    public function test_without_keys_recaptcha_is_off_and_registration_works(): void
    {
        config(['services.recaptcha.site_key' => null, 'services.recaptcha.secret_key' => null]);

        $this->get(route('register'))->assertOk()->assertDontSee('g-recaptcha', false);
        $this->post(route('register'), $this->form())->assertSessionHasNoErrors();

        $this->assertTrue(User::where('email', 'client@example.com')->exists());
        Http::assertNothingSent();
    }

    public function test_with_keys_the_checkbox_is_required(): void
    {
        config(['services.recaptcha.site_key' => 'site-key', 'services.recaptcha.secret_key' => 'secret-key']);

        $this->get(route('register'))->assertSee('data-sitekey="site-key"', false);
        $this->post(route('register'), $this->form())->assertSessionHasErrors('g-recaptcha-response');

        $this->assertFalse(User::where('email', 'client@example.com')->exists());
    }

    public function test_with_keys_google_must_confirm_the_answer(): void
    {
        config(['services.recaptcha.site_key' => 'site-key', 'services.recaptcha.secret_key' => 'secret-key']);

        $this->post(route('register'), $this->form(['g-recaptcha-response' => 'bot-answer']))
            ->assertSessionHasErrors('g-recaptcha-response');

        Http::assertSent(fn ($request) => $request['secret'] === 'secret-key');
        $this->assertFalse(User::where('email', 'client@example.com')->exists());
    }
}
