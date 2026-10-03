<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Consultant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_approval_always_requires_payment_even_for_test_looking_consultants(): void
    {
        $consultant = Consultant::factory()
            ->for(User::factory()->counselor()->state(['email' => 'consultant@test.com']))
            ->create(['price_per_30_min' => 0]);
        $booking = Booking::factory()->for($consultant)->create(['price' => 0]);

        $this->actingAs($consultant->user)
            ->post(route('consultant.booking.approve', $booking))
            ->assertRedirect();

        $booking->refresh();
        $this->assertSame('approved', $booking->status);
        $this->assertSame('pending', $booking->payment_status);
        $this->assertNull($booking->paid_at);
    }

    public function test_consultant_cannot_approve_another_consultants_booking(): void
    {
        $booking = Booking::factory()->create();
        $otherConsultant = Consultant::factory()->create();

        $this->actingAs($otherConsultant->user)
            ->post(route('consultant.booking.approve', $booking))
            ->assertForbidden();

        $this->assertSame('pending_approval', $booking->fresh()->status);
    }
}
