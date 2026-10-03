<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TestingInfrastructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_suite_runs_against_the_isolated_testing_database(): void
    {
        $this->assertSame('testing', app()->environment());
        $this->assertSame(TestCase::TESTING_DATABASE, DB::connection()->getDatabaseName());
    }

    public function test_factories_build_a_paid_booking_with_its_relations(): void
    {
        $payment = Payment::factory()->completed()->create();
        $booking = $payment->booking;

        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('paid', $booking->payment_status);
        $this->assertSame($booking->user_id, $payment->user_id);
        $this->assertSame('client', $booking->user->role);
        $this->assertSame('counselor', $booking->consultant->user->role);
        $this->assertSame(1, Booking::count());
    }
}
