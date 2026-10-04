<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\Booking;
use App\Models\Consultant;
use App\Models\ContentItem;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class CleanupTest extends TestCase
{
    use RefreshDatabase;

    // ── Password reset (was failing on a missing column) ─────────────────

    public function test_forgot_password_flow_changes_the_password(): void
    {
        Mail::fake();
        $user = User::factory()->create(['password' => 'Old-pass-123']);

        $this->post(route('password.email'), ['email' => $user->email])->assertSessionHasNoErrors();
        $token = $user->fresh()->password_reset_token;
        $this->assertNotNull($token);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'New-pass-456',
            'password_confirmation' => 'New-pass-456',
        ])->assertRedirect(route('login'));

        $user->refresh();
        $this->assertTrue(Hash::check('New-pass-456', $user->password));
        $this->assertNotNull($user->password_changed_at);
        $this->assertNull($user->password_reset_token);
    }

    // ── Admin profile ────────────────────────────────────────────────────

    public function test_admin_profile_update_is_validated_and_changes_the_password(): void
    {
        $admin = User::factory()->admin()->create();
        $other = User::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.profile.update'), ['name' => 'Admin', 'email' => $other->email])
            ->assertSessionHasErrors('email');

        $this->actingAs($admin)
            ->put(route('admin.profile.update'), ['name' => 'Admin', 'email' => $admin->email, 'password' => 'short', 'password_confirmation' => 'short'])
            ->assertSessionHasErrors('password');

        $this->actingAs($admin)
            ->put(route('admin.profile.update'), [
                'name' => 'المدير', 'email' => 'NEW@Example.com',
                'password' => 'Strong-pass-789', 'password_confirmation' => 'Strong-pass-789',
            ])
            ->assertSessionHasNoErrors();

        $admin->refresh();
        $this->assertSame('new@example.com', $admin->email);
        $this->assertTrue(Hash::check('Strong-pass-789', $admin->password));
    }

    // ── Admin panel leftovers ────────────────────────────────────────────

    public function test_old_settings_page_redirects_to_site_settings_and_security_pages_are_gone(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get(route('admin.settings'))->assertRedirect(route('admin.site-settings.index'));
        foreach (['security/logs', 'security/activity', 'security/blocked-ips'] as $path) {
            $this->get('/'.config('app.admin_path').'/'.$path)->assertNotFound();
        }
    }

    public function test_every_scheduled_command_exists(): void
    {
        $registered = array_keys(Artisan::all());

        foreach (app(Schedule::class)->events() as $event) {
            preg_match('/artisan["\']?\s+["\']?([\w:-]+)/', $event->command, $match);
            $this->assertContains($match[1], $registered, "Scheduled command {$match[1]} does not exist");
        }
    }

    // ── Seeders ──────────────────────────────────────────────────────────

    public function test_content_seeder_never_wipes_existing_content(): void
    {
        ContentItem::create(['type' => 'stat', 'page' => 'home', 'title' => 'معدّل من الإدارة', 'order' => 1, 'is_active' => true]);

        $this->artisan('db:seed', ['--class' => 'ContentItemsSeeder', '--force' => true])->assertSuccessful();

        $this->assertSame(1, ContentItem::count());
        $this->assertSame('معدّل من الإدارة', ContentItem::first()->title);
    }

    public function test_database_seeder_fills_a_fresh_environment(): void
    {
        $this->artisan('db:seed', ['--force' => true])->assertSuccessful();

        $this->assertGreaterThan(0, Assessment::count());
        $this->assertGreaterThan(0, ContentItem::count());
        $this->assertGreaterThan(0, SiteSetting::count());
    }

    public function test_webrtc_signals_table_is_dropped(): void
    {
        $this->assertFalse(Schema::hasTable('video_call_signals'));
    }

    // ── Pages ────────────────────────────────────────────────────────────

    public function test_payment_page_lists_accepted_methods_without_fake_choices(): void
    {
        $booking = Booking::factory()->approved()->create();

        $this->actingAs($booking->user)
            ->get(route('consultations.payment', $booking))
            ->assertOk()
            ->assertSee('طرق الدفع المقبولة')
            ->assertDontSee('name="payment_method"', false);
    }

    public function test_contact_whatsapp_uses_the_site_setting(): void
    {
        SiteSetting::set('whatsapp', '+966 55 123 4567');

        $this->get(route('contact'))->assertOk()->assertSee('https://wa.me/966551234567?text=', false);
    }

    public function test_consultant_forms_no_longer_ask_for_a_meeting_link(): void
    {
        $consultant = Consultant::factory()->create();

        $this->actingAs($consultant->user)->get(route('consultant.profile'))->assertOk()->assertDontSee('name="meeting_link"', false);

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get(route('admin.consultants.create'))->assertOk()->assertDontSee('name="meeting_link"', false);
        $this->actingAs($admin)->get(route('admin.consultants.edit', $consultant))->assertOk()->assertDontSee('name="meeting_link"', false);
    }
}
