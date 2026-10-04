<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The control panel is for admins only; consultants (counselor role) have /consultant.
 */
class AdminPaymentAccessTest extends TestCase
{
    use RefreshDatabase;

    public static function controlPanelRoutes(): array
    {
        return [
            'dashboard' => ['admin.dashboard'],
            'payment settings' => ['admin.payment-settings.index'],
            'transactions' => ['admin.payment-settings.transactions'],
            'finance' => ['admin.finance.index'],
            'users' => ['admin.users.index'],
            'bookings' => ['admin.bookings.index'],
            'attempts' => ['admin.attempts.index'],
        ];
    }

    /** @dataProvider controlPanelRoutes */
    public function test_counselors_cannot_reach_the_control_panel(string $route): void
    {
        $this->actingAs(User::factory()->counselor()->create())
            ->get(route($route))
            ->assertRedirect(route('home'));
    }

    /** @dataProvider controlPanelRoutes */
    public function test_clients_cannot_reach_the_control_panel(string $route): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route($route))
            ->assertRedirect(route('home'));
    }

    /** @dataProvider controlPanelRoutes */
    public function test_admins_can_reach_the_control_panel(string $route): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route($route))
            ->assertOk();
    }

    public function test_counselors_keep_their_own_consultant_panel(): void
    {
        $consultant = \App\Models\Consultant::factory()->create();

        $this->actingAs($consultant->user)
            ->get(route('consultant.dashboard'))
            ->assertOk();
    }

    public function test_control_panel_lives_under_the_configured_admin_path(): void
    {
        $this->assertSame(url(config('app.admin_path')), route('admin.dashboard'));
        $this->assertSame('control-panel', config('app.admin_path'));
    }
}
