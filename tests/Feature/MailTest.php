<?php

namespace Tests\Feature;

use App\Mail\BookingApprovalRequest;
use App\Mail\BookingApprovalResponse;
use App\Mail\BookingConfirmation;
use App\Mail\BookingPendingNotification;
use App\Mail\ConsultantEarnings;
use App\Mail\InvoiceEmail;
use App\Mail\PasswordResetEmail;
use App\Mail\WelcomeEmail;
use App\Models\Consultant;
use App\Models\ConsultantSchedule;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Transport\ResendTransport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class MailTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_mailable_is_queued(): void
    {
        foreach (glob(app_path('Mail/*.php')) as $file) {
            $class = 'App\\Mail\\'.basename($file, '.php');
            $this->assertTrue(is_subclass_of($class, ShouldQueue::class), "{$class} must implement ShouldQueue");
        }
    }

    public function test_sending_an_email_puts_a_job_on_the_database_queue(): void
    {
        config(['queue.default' => 'database']);
        $user = User::factory()->create();

        Mail::to($user->email)->send(new WelcomeEmail($user, 'https://example.test/verify'));

        $this->assertSame(1, DB::table('jobs')->count());
    }

    public function test_resend_is_the_configured_mailer_when_selected(): void
    {
        config(['mail.default' => 'resend', 'services.resend.key' => 're_test_key']);

        $this->assertInstanceOf(ResendTransport::class, Mail::mailer()->getSymfonyTransport());
    }

    /**
     * Every link inside every email must open an existing page
     * (several pointed to URLs that did not exist: /client/sessions, /client/invoices...).
     */
    public function test_all_email_links_point_to_existing_pages(): void
    {
        $payment = Payment::factory()->completed()->create();
        $booking = $payment->booking;
        $user = $booking->user;

        $mailables = [
            new BookingApprovalRequest($booking),
            new BookingApprovalResponse($booking, true),
            new BookingApprovalResponse($booking, false),
            new BookingConfirmation($booking, 'client'),
            new BookingConfirmation($booking, 'consultant'),
            new BookingPendingNotification($booking),
            new ConsultantEarnings($booking->consultant, $booking, 92.0),
            new InvoiceEmail($payment),
            new PasswordResetEmail($user, route('password.reset', 'token123')),
            new WelcomeEmail($user, route('verification.verify', 'token123')),
        ];

        $checked = 0;
        foreach ($mailables as $mailable) {
            foreach ($this->internalLinks($mailable) as $url) {
                $this->assertRouteExists($url, $mailable::class);
                $checked++;
            }
        }

        $this->assertGreaterThanOrEqual(9, $checked);
    }

    public function test_key_email_buttons_use_the_right_pages(): void
    {
        $payment = Payment::factory()->completed()->create();
        $booking = $payment->booking;

        $this->assertStringContainsString(route('client.sessions'), (new BookingConfirmation($booking, 'client'))->render());
        $this->assertStringContainsString(route('client.invoices'), (new InvoiceEmail($payment))->render());
        $this->assertStringContainsString(route('consultations.payment', $booking), (new BookingApprovalResponse($booking, true))->render());
    }

    public function test_booking_request_queues_emails_to_consultant_and_client(): void
    {
        Mail::fake();
        $consultant = Consultant::factory()->create();
        $date = now()->addDays(3);
        ConsultantSchedule::create([
            'consultant_id' => $consultant->id,
            'day_of_week' => $date->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '17:00',
            'is_available' => true,
        ]);
        $client = User::factory()->create();

        $this->actingAs($client)
            ->post(route('consultations.book', $consultant), [
                'booking_date' => $date->toDateString(),
                'start_time' => '10:00',
                'duration' => '30',
            ])
            ->assertRedirect();

        Mail::assertQueued(BookingApprovalRequest::class, fn ($mail) => $mail->hasTo($consultant->user->email));
        Mail::assertQueued(BookingPendingNotification::class, fn ($mail) => $mail->hasTo($client->email));
    }

    private function internalLinks(Mailable $mailable): array
    {
        preg_match_all('/href="([^"]+)"/', $mailable->render(), $matches);

        return array_values(array_filter(
            array_map('html_entity_decode', $matches[1]),
            fn ($url) => str_starts_with($url, config('app.url'))
        ));
    }

    private function assertRouteExists(string $url, string $mailable): void
    {
        try {
            Route::getRoutes()->match(Request::create($url));
            $this->addToAssertionCount(1);
        } catch (NotFoundHttpException) {
            $this->fail("{$mailable} links to a page that does not exist: {$url}");
        }
    }
}
