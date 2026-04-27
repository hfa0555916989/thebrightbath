<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display contact form
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'message.required' => 'الرسالة مطلوبة',
        ]);

        ContactMessage::create($validated);

        return redirect()->route('contact')
            ->with('success', 'شكراً لتواصلك معنا! سنرد عليك في أقرب وقت ممكن.');
    }
}






