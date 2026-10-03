<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPaymentAccessTest extends TestCase
{
    use RefreshDatabase;

    public static function adminOnlyRoutes(): array
    {
        return [
            'payment settings' => ['admin.payment-settings.index'],
            'transactions' => ['admin.payment-settings.transactions'],
            'finance' => ['admin.finance.index'],
            'finance settings' => ['admin.finance.settings'],
        ];
    }

    /** @dataProvider adminOnlyRoutes */
    public function test_counselors_cannot_reach_payment_and_finance_pages(string $route): void
    {
        $this->actingAs(User::factory()->counselor()->create())
            ->get(route($route))
            ->assertRedirect(route('home'));
    }

    /** @dataProvider adminOnlyRoutes */
    public function test_admins_can_reach_payment_and_finance_pages(string $route): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route($route))
            ->assertOk();
    }

    public function test_counselors_keep_access_to_the_rest_of_the_control_panel(): void
    {
        $this->actingAs(User::factory()->counselor()->create())
            ->get(route('admin.attempts.index'))
            ->assertOk();
    }
}
