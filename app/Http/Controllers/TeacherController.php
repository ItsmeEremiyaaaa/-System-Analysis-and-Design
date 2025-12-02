<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Teacher;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\Notification;

class TeacherController extends Controller
{
    // ==================== TEACHER AUTHENTICATION METHODS ====================
    
    public function showLoginForm()
    {
        return view('TeachersLogin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Teacher authentication logic
        if (auth()->guard('teacher')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('teacher.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function dashboard()
    {
        $teacher = Auth::guard('teacher')->user();
        return view('TeachersDashboard', compact('teacher'));
    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/teacher/login');
    }
    
    // Optional: Add profile methods if needed
    public function profile()
    {
        $teacher = Auth::guard('teacher')->user();
        return view('TeachersDashboard', compact('teacher'));
    }

    // ==================== TEACHER DASHBOARD FUNCTIONALITY ====================

    /**
     * Store a new event/class
     */
    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable|string',
            'lab' => 'nullable|string'
        ]);

        try {
            $event = Event::create([
                'title' => $request->title,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'description' => $request->description,
                'lab' => $request->lab,
                'teacher_id' => Auth::guard('teacher')->id(),
                'status' => 'pending',
                'created_by' => 'teacher'
            ]);

            // Create notification for admin
            Notification::create([
                'user_id' => 1, // Admin user ID
                'title' => 'New Class Request',
                'message' => Auth::guard('teacher')->user()->full_name . ' has requested to schedule a class: ' . $request->title,
                'type' => 'class_request',
                'related_id' => $event->id,
                'related_type' => 'event'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Class submitted for approval successfully!',
                'data' => $event
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new reservation
     */
    public function storeReservation(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'lab' => 'required|string',
            'purpose' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'participants' => 'nullable|integer',
            'requirements' => 'nullable|string'
        ]);

        try {
            $reservation = Reservation::create([
                'title' => $request->title,
                'lab' => $request->lab,
                'purpose' => $request->purpose,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'participants' => $request->participants,
                'requirements' => $request->requirements,
                'teacher_id' => Auth::guard('teacher')->id(),
                'status' => 'pending'
            ]);

            // Create notification for admin
            Notification::create([
                'user_id' => 1, // Admin user ID
                'title' => 'New Lab Reservation Request',
                'message' => Auth::guard('teacher')->user()->full_name . ' has requested to reserve ' . $request->lab . ' for ' . $request->title,
                'type' => 'reservation_request',
                'related_id' => $reservation->id,
                'related_type' => 'reservation'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reservation submitted for approval successfully!',
                'data' => $reservation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting reservation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time updates for teacher
     */
    public function getUpdates(Request $request)
    {
        $teacherId = Auth::guard('teacher')->id();
        
        // Get unread notifications
        $notifications = Notification::where('user_id', $teacherId)
            ->where('read', false)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'timestamp' => $notification->created_at->toISOString(),
                    'read' => $notification->read
                ];
            });

        // Get updated reservations
        $reservations = Reservation::where('teacher_id', $teacherId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'title' => $reservation->title,
                    'lab' => $reservation->lab,
                    'purpose' => $reservation->purpose,
                    'date' => $reservation->date,
                    'startTime' => $reservation->start_time,
                    'endTime' => $reservation->end_time,
                    'status' => $reservation->status,
                    'adminFeedback' => $reservation->admin_feedback,
                    'requestedAt' => $reservation->created_at->toISOString()
                ];
            });

        // Get updated events
        $classes = Event::where('teacher_id', $teacherId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'date' => $event->date,
                    'startTime' => $event->start_time,
                    'endTime' => $event->end_time,
                    'description' => $event->description,
                    'lab' => $event->lab,
                    'status' => $event->status,
                    'createdAt' => $event->created_at->toISOString()
                ];
            });

        return response()->json([
            'success' => true,
            'updates' => [
                'notifications' => $notifications,
                'reservations' => $reservations,
                'classes' => $classes
            ]
        ]);
    }

    // ==================== ADMIN TEACHER MANAGEMENT METHODS ====================
    
    /**
     * Display a listing of teachers
     */
    public function index()
    {
        $teachers = Teacher::all();
        return view('admin.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher
     */
    public function create()
    {
        return view('TeacherRegister');
    }

    /**
     * Store a newly created teacher
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers|ends_with:@smcbi.edu.ph',
            'course' => 'required|string|max:255',
            'department' => 'required|in:BSIT,BSBA,BEED,BSHM,BSED',
            'cellphone' => 'nullable|string|max:11|min:11',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'email.ends_with' => 'Email must end with @smcbi.edu.ph',
            'department.in' => 'Please select a valid department',
            'password.regex' => 'Password must include uppercase, lowercase, number and special character',
        ]);

        // Handle profile photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile-photos', 'public');
        }

        Teacher::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'course' => $validated['course'],
            'department' => $validated['department'],
            'cellphone' => $validated['cellphone'],
            'password' => Hash::make($validated['password']),
            'photo' => $photoPath,
        ]);

        // REDIRECT TO TEACHER LOGIN PAGE after creation
        return redirect()->route('teacher.login')
            ->with('success', 'Teacher account created successfully! The teacher can now login.');
    }

    /**
     * Display the specified teacher
     */
    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('admin.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher
     */
    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('admin.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $id,
            'course' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'cellphone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $updateData = [
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'course' => $validated['course'],
            'department' => $validated['department'],
            'cellphone' => $validated['cellphone'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $teacher->update($updateData);

        return redirect()->route('manage-teachers.index')
            ->with('success', 'Teacher account updated successfully!');
    }

    /**
     * Remove the specified teacher
     */
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('manage-teachers.index')
            ->with('success', 'Teacher account deleted successfully!');
    }

    /**
     * API endpoint for teachers data
     */
    public function apiIndex()
    {
        $teachers = Teacher::all();
        
        return response()->json([
            'success' => true,
            'data' => $teachers
        ]);
    }
}

