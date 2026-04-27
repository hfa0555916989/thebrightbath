<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\VideoCall;
use App\Models\VideoCallSignal;
use App\Models\VideoCallMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoCallController extends Controller
{
    /**
     * عرض غرفة المكالمة
     */
    public function join(Booking $booking)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول للانضمام إلى الجلسة.');
        }

        // التحقق من أن الحجز مؤكد أو مدفوع
        if (!in_array($booking->status, ['confirmed', 'completed', 'paid'])) {
            // إذا كان pending_payment، توجيه للدفع
            if ($booking->status === 'pending_payment') {
                return redirect()->route('consultations.payment', $booking)
                    ->with('info', 'يرجى إكمال الدفع أولاً للانضمام للجلسة.');
            }
            return back()->with('error', 'لا يمكن الانضمام إلى جلسة غير مؤكدة.');
        }

        // التحقق من صلاحية المستخدم
        $user = Auth::user();
        $isClient = $user->id === $booking->user_id;
        $isConsultant = $booking->consultant && $user->id === $booking->consultant->user_id;

        if (!$isClient && !$isConsultant) {
            abort(403, 'ليس لديك صلاحية الوصول لهذه الجلسة.');
        }

        // إنشاء أو جلب غرفة المكالمة
        $videoCall = VideoCall::getOrCreateForBooking($booking);

        // تحديد الطرف الآخر
        $otherUserId = $isClient ? $booking->consultant->user_id : $booking->user_id;
        $otherUserName = $isClient ? $booking->consultant->user->name : $booking->user->name;

        // جلب الرسائل السابقة
        $messages = VideoCallMessage::where('video_call_id', $videoCall->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('video-call.room', [
            'booking' => $booking,
            'videoCall' => $videoCall,
            'currentUserId' => $user->id,
            'otherUserId' => $otherUserId,
            'userName' => $user->name,
            'otherUserName' => $otherUserName,
            'isConsultant' => $isConsultant,
            'messages' => $messages,
        ]);
    }

    /**
     * إرسال إشارة WebRTC
     */
    public function sendSignal(Request $request, VideoCall $videoCall)
    {
        $request->validate([
            'type' => 'required|in:offer,answer,ice_candidate',
            'data' => 'required',
            'to_user_id' => 'required|exists:users,id',
        ]);

        VideoCallSignal::create([
            'video_call_id' => $videoCall->id,
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'type' => $request->type,
            'data' => is_string($request->data) ? $request->data : json_encode($request->data),
        ]);

        if ($request->type === 'offer' && $videoCall->status === 'waiting') {
            $videoCall->start();
        }

        return response()->json(['success' => true]);
    }

    /**
     * جلب الإشارات الجديدة
     */
    public function getSignals(VideoCall $videoCall)
    {
        $signals = VideoCallSignal::where('video_call_id', $videoCall->id)
            ->where('to_user_id', Auth::id())
            ->where('is_read', false)
            ->orderBy('created_at', 'asc')
            ->get();

        VideoCallSignal::where('video_call_id', $videoCall->id)
            ->where('to_user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'signals' => $signals->map(function ($signal) {
                return [
                    'type' => $signal->type,
                    'data' => json_decode($signal->data, true) ?? $signal->data,
                    'from_user_id' => $signal->from_user_id,
                ];
            }),
        ]);
    }

    /**
     * إرسال رسالة دردشة
     */
    public function sendMessage(Request $request, VideoCall $videoCall)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $message = VideoCallMessage::create([
            'video_call_id' => $videoCall->id,
            'user_id' => Auth::id(),
            'type' => 'text',
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'type' => $message->type,
                'user_id' => $message->user_id,
                'user_name' => Auth::user()->name,
                'created_at' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    /**
     * جلب الرسائل الجديدة
     */
    public function getMessages(VideoCall $videoCall, Request $request)
    {
        $lastId = $request->get('last_id', 0);

        $messages = VideoCallMessage::where('video_call_id', $videoCall->id)
            ->where('id', '>', $lastId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'content' => $msg->content,
                    'type' => $msg->type,
                    'user_id' => $msg->user_id,
                    'user_name' => $msg->user->name,
                    'file_name' => $msg->file_name,
                    'file_path' => $msg->file_path ? asset('storage/' . $msg->file_path) : null,
                    'file_size' => $msg->file_size_formatted,
                    'created_at' => $msg->created_at->format('H:i'),
                ];
            }),
        ]);
    }

    /**
     * رفع ملف
     */
    public function uploadFile(Request $request, VideoCall $videoCall)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        $file = $request->file('file');
        $path = $file->store('video-call-files/' . $videoCall->id, 'public');

        $message = VideoCallMessage::create([
            'video_call_id' => $videoCall->id,
            'user_id' => Auth::id(),
            'type' => 'file',
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'type' => 'file',
                'user_id' => $message->user_id,
                'user_name' => Auth::user()->name,
                'file_name' => $message->file_name,
                'file_path' => asset('storage/' . $message->file_path),
                'file_size' => $message->file_size_formatted,
                'created_at' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    /**
     * التحقق من حالة المكالمة
     */
    public function checkStatus(VideoCall $videoCall)
    {
        return response()->json([
            'status' => $videoCall->status,
            'started_at' => $videoCall->started_at,
        ]);
    }

    /**
     * إنهاء المكالمة
     */
    public function end(Booking $booking)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $user = Auth::user();
        $isClient = $user->id === $booking->user_id;
        $isConsultant = $booking->consultant && $user->id === $booking->consultant->user_id;

        if (!$isClient && !$isConsultant) {
            abort(403, 'ليس لديك صلاحية إنهاء هذه الجلسة.');
        }

        $videoCall = VideoCall::where('booking_id', $booking->id)->first();
        if ($videoCall) {
            $videoCall->end();
        }

        if (in_array($booking->status, ['confirmed', 'paid'])) {
            $booking->update(['status' => 'completed']);
        }

        $redirectRoute = $isConsultant ? 'consultant.dashboard' : 'client.dashboard';
        return redirect()->route($redirectRoute)->with('success', 'تم إنهاء الجلسة بنجاح.');
    }
}
