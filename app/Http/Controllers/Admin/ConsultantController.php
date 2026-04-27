<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultant;
use App\Models\ConsultantSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ConsultantController extends Controller
{
    /**
     * Display a listing of consultants.
     */
    public function index()
    {
        $consultants = Consultant::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.consultants.index', compact('consultants'));
    }

    /**
     * Show the form for creating a new consultant.
     */
    public function create()
    {
        return view('admin.consultants.create');
    }

    /**
     * Store a newly created consultant.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'specialization' => ['required', 'string', 'max:255'],
            'specialization_ar' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'bio_ar' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'price_per_30_min' => ['required', 'numeric', 'min:0'],
            'price_per_60_min' => ['required', 'numeric', 'min:0'],
            'experience_years' => ['required', 'integer', 'min:0'],
            'meeting_link' => ['nullable', 'url'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        // Create user account for consultant
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'counselor',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('consultants', 'public');
        }

        // Create consultant profile
        $consultant = Consultant::create([
            'user_id' => $user->id,
            'specialization' => $request->specialization,
            'specialization_ar' => $request->specialization_ar,
            'bio' => $request->bio,
            'bio_ar' => $request->bio_ar,
            'photo' => $photoPath,
            'price_per_30_min' => $request->price_per_30_min,
            'price_per_60_min' => $request->price_per_60_min,
            'experience_years' => $request->experience_years,
            'meeting_link' => $request->meeting_link,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        return redirect()->route('admin.consultants.index')
            ->with('success', 'تم إضافة المستشار بنجاح');
    }

    /**
     * Display the specified consultant.
     */
    public function show(Consultant $consultant)
    {
        $consultant->load(['user', 'schedules', 'bookings' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.consultants.show', compact('consultant'));
    }

    /**
     * Show the form for editing the consultant.
     */
    public function edit(Consultant $consultant)
    {
        $consultant->load('user');
        return view('admin.consultants.edit', compact('consultant'));
    }

    /**
     * Update the specified consultant.
     */
    public function update(Request $request, Consultant $consultant)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $consultant->user_id],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'specialization' => ['required', 'string', 'max:255'],
            'specialization_ar' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'bio_ar' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'price_per_30_min' => ['required', 'numeric', 'min:0'],
            'price_per_60_min' => ['required', 'numeric', 'min:0'],
            'experience_years' => ['required', 'integer', 'min:0'],
            'meeting_link' => ['nullable', 'url'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        // Update user account
        $userData = [
            'name' => $request->name,
            'email' => strtolower($request->email),
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $consultant->user->update($userData);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($consultant->photo) {
                Storage::disk('public')->delete($consultant->photo);
            }
            $photoPath = $request->file('photo')->store('consultants', 'public');
        } else {
            $photoPath = $consultant->photo;
        }

        // Update consultant profile
        $consultant->update([
            'specialization' => $request->specialization,
            'specialization_ar' => $request->specialization_ar,
            'bio' => $request->bio,
            'bio_ar' => $request->bio_ar,
            'photo' => $photoPath,
            'price_per_30_min' => $request->price_per_30_min,
            'price_per_60_min' => $request->price_per_60_min,
            'experience_years' => $request->experience_years,
            'meeting_link' => $request->meeting_link,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        return redirect()->route('admin.consultants.index')
            ->with('success', 'تم تحديث بيانات المستشار بنجاح');
    }

    /**
     * Remove the specified consultant.
     */
    public function destroy(Consultant $consultant)
    {
        // Delete photo
        if ($consultant->photo) {
            Storage::disk('public')->delete($consultant->photo);
        }

        // Delete user (will cascade to consultant)
        $consultant->user->delete();

        return redirect()->route('admin.consultants.index')
            ->with('success', 'تم حذف المستشار بنجاح');
    }

    /**
     * Manage consultant schedule.
     */
    public function schedule(Consultant $consultant)
    {
        $consultant->load('schedules');
        $days = ConsultantSchedule::$daysArabic;

        return view('admin.consultants.schedule', compact('consultant', 'days'));
    }

    /**
     * Update consultant schedule.
     */
    public function updateSchedule(Request $request, Consultant $consultant)
    {
        $request->validate([
            'schedules' => ['required', 'array'],
            'schedules.*.day_of_week' => ['required', 'integer', 'min:0', 'max:6'],
            'schedules.*.start_time' => ['required_if:schedules.*.is_available,true'],
            'schedules.*.end_time' => ['required_if:schedules.*.is_available,true'],
            'schedules.*.is_available' => ['boolean'],
        ]);

        // Delete existing schedules
        $consultant->schedules()->delete();

        // Create new schedules
        foreach ($request->schedules as $schedule) {
            if (!empty($schedule['is_available']) && !empty($schedule['start_time']) && !empty($schedule['end_time'])) {
                $consultant->schedules()->create([
                    'day_of_week' => $schedule['day_of_week'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'is_available' => true,
                ]);
            }
        }

        return redirect()->route('admin.consultants.schedule', $consultant)
            ->with('success', 'تم تحديث جدول المواعيد بنجاح');
    }

    /**
     * Toggle consultant active status.
     */
    public function toggleActive(Consultant $consultant)
    {
        $consultant->update(['is_active' => !$consultant->is_active]);

        $status = $consultant->is_active ? 'تفعيل' : 'تعطيل';
        return back()->with('success', "تم {$status} المستشار بنجاح");
    }
}





