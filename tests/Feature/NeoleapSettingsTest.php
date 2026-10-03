<?php

namespace Tests\Feature;

use App\Models\PaymentSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NeoleapSettingsTest extends TestCase
{
    use RefreshDatabase;

    private const RESOURCE_KEY = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456';

    private function validSettings(array $overrides = []): array
    {
        return array_merge([
            'tranportal_id' => 'IPAYtestTerminal',
            'tranportal_password' => 'secret-pass-123',
            'resource_key' => self::RESOURCE_KEY,
            'endpoint_url' => 'https://pg.test/pg/payment/hosted.htm',
            'is_sandbox' => '1',
            'is_active' => '1',
        ], $overrides);
    }

    public function test_admin_saves_credentials_and_secrets_are_encrypted_at_rest(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.payment-settings.update'), $this->validSettings())
            ->assertRedirect(route('admin.payment-settings.index'));

        $settings = PaymentSetting::where('gateway', 'neoleap')->sole();
        $this->assertTrue($settings->is_active);
        $this->assertSame('secret-pass-123', $settings->tranportal_password);
        $this->assertSame(self::RESOURCE_KEY, $settings->resource_key);

        $raw = DB::table('payment_settings')->where('gateway', 'neoleap')->first();
        $this->assertNotSame('secret-pass-123', $raw->tranportal_password);
        $this->assertNotSame(self::RESOURCE_KEY, $raw->resource_key);
    }

    public function test_settings_page_never_prints_the_secrets(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->put(route('admin.payment-settings.update'), $this->validSettings());

        $this->actingAs($admin)
            ->get(route('admin.payment-settings.index'))
            ->assertOk()
            ->assertSee('IPAYtestTerminal')
            ->assertSee(route('payment.neoleap.response'))
            ->assertDontSee('secret-pass-123')
            ->assertDontSee(self::RESOURCE_KEY);
    }

    public function test_leaving_secret_fields_blank_keeps_the_saved_values(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->put(route('admin.payment-settings.update'), $this->validSettings());

        $this->actingAs($admin)
            ->put(route('admin.payment-settings.update'), $this->validSettings([
                'tranportal_password' => '',
                'resource_key' => '',
                'endpoint_url' => 'https://pg.test/live/hosted.htm',
            ]))
            ->assertRedirect(route('admin.payment-settings.index'));

        $settings = PaymentSetting::where('gateway', 'neoleap')->sole();
        $this->assertSame('secret-pass-123', $settings->tranportal_password);
        $this->assertSame(self::RESOURCE_KEY, $settings->resource_key);
        $this->assertSame('https://pg.test/live/hosted.htm', $settings->endpoint_url);
    }

    public function test_gateway_cannot_be_activated_with_missing_credentials(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.payment-settings.update'), $this->validSettings(['resource_key' => '']))
            ->assertSessionHas('error');

        $this->assertFalse(PaymentSetting::where('gateway', 'neoleap')->exists());
    }

    public function test_resource_key_must_be_32_characters(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.payment-settings.update'), $this->validSettings(['resource_key' => 'too-short']))
            ->assertSessionHasErrors('resource_key');
    }

    public function test_connection_test_reports_gateway_answer(): void
    {
        Http::fake(['pg.test/*' => Http::response([[
            'status' => '1', 'result' => '600202412345678901:https://pg.test/paymentpage.htm', 'error' => null, 'errorText' => null,
        ]])]);
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->put(route('admin.payment-settings.update'), $this->validSettings(['is_active' => '0']));

        $this->actingAs($admin)
            ->postJson(route('admin.payment-settings.test'))
            ->assertOk()
            ->assertJson(['success' => true]);
    }
}
