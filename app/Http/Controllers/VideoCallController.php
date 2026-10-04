<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\VideoCall;
use App\Models\VideoCallMessage;
use App\Services\DailyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Session page: Daily.co video room + files shared between client and consultant.
 */
class VideoCallController extends Controller
{
    /**
     * Files people may share in a session (10 MB max).
     */
    private const FILE_TYPES = 'pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png';

    /**
     * عرض صفحة الجلسة
     */
    public function join(Booking $booking, DailyService $daily)
    {
        $isConsultant = $this->authorizeParticipant($booking);

        // التحقق من أن الحجز مؤكد ومدفوع
        if (!in_array($booking->status, ['confirmed', 'completed'], true)) {
            if ($booking->status === 'approved') {
                return redirect()->route('consultations.payment', $booking)
                    ->with('info', 'يرجى إكمال الدفع أولاً للانضمام للجلسة.');
            }

            return back()->with('error', 'لا يمكن الانضمام إلى جلسة غير مؤكدة.');
        }

        $booking->load(['consultant.user', 'user']);
        $videoCall = VideoCall::getOrCreateForBooking($booking);

        $state = match (true) {
            $booking->status === 'completed' || now()->gt($booking->joinClosesAt()) => 'ended',
            now()->lt($booking->joinOpensAt()) => 'waiting',
            !$daily->isConfigured() => 'unavailable',
            default => 'live',
        };

        $roomUrl = null;
        $token = null;

        if ($state === 'live') {
            try {
                $videoCall = $daily->roomFor($booking);
                $token = $daily->tokenFor($videoCall, Auth::user(), $isConsultant);
                $roomUrl = $videoCall->daily_room_url;
                $videoCall->start();
            } catch (\Throwable $e) {
                Log::error('Daily: could not open session room', ['booking_id' => $booking->id, 'error' => $e->getMessage()]);
                $state = 'unavailable';
            }
        }

        return view('video-call.room', [
            'booking' => $booking,
            'videoCall' => $videoCall,
            'state' => $state,
            'roomUrl' => $roomUrl,
            'token' => $token,
            'isConsultant' => $isConsultant,
            'otherUserName' => $isConsultant ? $booking->user->name : $booking->consultant->user->name,
            'files' => $this->fileList($videoCall),
        ]);
    }

    /**
     * إنهاء الجلسة (المستشار ينهيها للجميع)
     */
    public function end(Booking $booking, DailyService $daily)
    {
        $isConsultant = $this->authorizeParticipant($booking);

        if ($isConsultant) {
            $videoCall = VideoCall::where('booking_id', $booking->id)->first();
            if ($videoCall) {
                $videoCall->end();
                $daily->deleteRoom($videoCall);
            }

            if ($booking->status === 'confirmed') {
                $booking->update(['status' => 'completed']);
            }
        }

        $redirectRoute = $isConsultant ? 'consultant.dashboard' : 'client.dashboard';

        return redirect()->route($redirectRoute)->with('success', 'تم إنهاء الجلسة بنجاح.');
    }

    /**
     * قائمة ملفات الجلسة
     */
    public function files(VideoCall $videoCall)
    {
        $this->authorizeParticipant($videoCall->booking);

        return response()->json(['files' => $this->fileList($videoCall)]);
    }

    /**
     * رفع ملف للجلسة
     */
    public function uploadFile(Request $request, VideoCall $videoCall)
    {
        $this->authorizeParticipant($videoCall->booking);

        $request->validate([
            'file' => 'required|file|max:10240|mimes:'.self::FILE_TYPES,
        ], [
            'file.mimes' => 'نوع الملف غير مسموح. المسموح: PDF وWord وExcel وPowerPoint والصور والنصوص.',
            'file.max' => 'حجم الملف يتجاوز 10 ميجابايت.',
        ]);

        $file = $request->file('file');
        $path = Storage::disk('private')->putFileAs(
            'session-files/'.$videoCall->id,
            $file,
            Str::random(40).'.'.$file->extension()
        );

        $message = VideoCallMessage::create([
            'video_call_id' => $videoCall->id,
            'user_id' => Auth::id(),
            'type' => 'file',
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return response()->json(['success' => true, 'file' => $this->fileData($message->load('user'))]);
    }

    /**
     * تحميل ملف (لطرفي الجلسة فقط)
     */
    public function downloadFile(VideoCallMessage $message)
    {
        $this->authorizeParticipant($message->videoCall->booking);

        abort_unless($message->type === 'file' && Storage::disk('private')->exists($message->file_path), 404);

        return Storage::disk('private')->download($message->file_path, $message->file_name);
    }

    /**
     * السماح فقط لطرفي الجلسة (العميل والمستشار). يعيد true إذا كان المستخدم هو المستشار.
     */
    private function authorizeParticipant(?Booking $booking): bool
    {
        $userId = Auth::id();
        $isClient = $booking && $booking->user_id === $userId;
        $isConsultant = $booking && $booking->consultant?->user_id === $userId;

        abort_unless($isClient || $isConsultant, 403, 'ليس لديك صلاحية الوصول لهذه الجلسة.');

        return $isConsultant;
    }

    private function fileList(VideoCall $videoCall): array
    {
        return $videoCall->messages()
            ->where('type', 'file')
            ->with('user')
            ->orderBy('created_at')
            ->get()
            ->map(fn ($message) => $this->fileData($message))
            ->all();
    }

    private function fileData(VideoCallMessage $message): array
    {
        return [
            'id' => $message->id,
            'name' => $message->file_name,
            'size' => $message->file_size_formatted,
            'user_name' => $message->user->name,
            'mine' => $message->user_id === Auth::id(),
            'time' => $message->created_at->format('H:i'),
            'url' => route('video-call.download-file', $message),
        ];
    }
}
