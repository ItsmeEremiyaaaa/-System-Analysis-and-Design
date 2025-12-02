<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminRegisterController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ManageStaffController;
use App\Http\Controllers\ManageStudentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ComputerReservationController;

// ==================== BASIC TEST ROUTE ====================
Route::get('/test-teacher', function() {
    return "Basic route test - working!";
});

// ==================== TEACHER ROUTES ====================

// Teacher Authentication Routes (Public - no auth required)
Route::prefix('teacher')->group(function () {
    // Login Routes
    Route::get('/login', [TeacherController::class, 'showLoginForm'])->name('teacher.login');
    Route::post('/login', [TeacherController::class, 'login'])->name('teacher.login.submit');
    
    // Registration Routes 
    Route::get('/register', [TeacherController::class, 'create'])->name('teacher.register');
    Route::post('/register', [TeacherController::class, 'store'])->name('teacher.register.submit');
});

// Protected Teacher Routes (requires teacher authentication)
Route::prefix('teacher')->middleware(['auth:teacher'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    
    // Events/Classes
    Route::post('/events', [TeacherController::class, 'storeEvent'])->name('teacher.events.store');
    
    // Reservations
    Route::post('/reservations', [TeacherController::class, 'storeReservation'])->name('teacher.reservations.store');
    
    // Updates
    Route::get('/updates', [TeacherController::class, 'getUpdates'])->name('teacher.updates');
    
    // Logout
    Route::post('/logout', [TeacherController::class, 'logout'])->name('teacher.logout');
});

// ==================== ADMIN ROUTES ====================

// Admin Registration Routes
Route::get('/admin/register', [AdminRegisterController::class, 'showRegistrationForm'])->name('admin.register');
Route::post('/admin/register', [AdminRegisterController::class, 'register'])->name('admin.register.submit');

// Admin Login Routes  
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Dashboard Route
Route::get('/admin/dashboard', function () {
    return view('MainDashboard');
})->name('admin.dashboard');

// Protected Admin Routes
Route::prefix('admin')->group(function () {
    // Manage Staff Routes
    Route::prefix('manage-staff')->group(function () {
        Route::get('/', [ManageStaffController::class, 'index'])->name('manage-staff.index');
        Route::get('/create', [ManageStaffController::class, 'create'])->name('manage-staff.create');
        Route::post('/', [ManageStaffController::class, 'store'])->name('manage-staff.store');
        Route::get('/{id}', [ManageStaffController::class, 'show'])->name('manage-staff.show');
        Route::get('/{id}/edit', [ManageStaffController::class, 'edit'])->name('manage-staff.edit');
        Route::put('/{id}', [ManageStaffController::class, 'update'])->name('manage-staff.update');
        Route::delete('/{id}', [ManageStaffController::class, 'destroy'])->name('manage-staff.destroy');
        
        Route::get('/api/manage-staff', [ManageStaffController::class, 'apiIndex'])->name('manage-staff.api.index');
    });

    // Manage Students Routes
    Route::prefix('manage-students')->group(function () {
        Route::get('/', [ManageStudentController::class, 'index'])->name('manage-students.index');
        Route::get('/create', [ManageStudentController::class, 'create'])->name('manage-students.create');
        Route::post('/', [ManageStudentController::class, 'store'])->name('manage-students.store');
        Route::get('/{id}', [ManageStudentController::class, 'show'])->name('manage-students.show');
        Route::get('/{id}/edit', [ManageStudentController::class, 'edit'])->name('manage-students.edit');
        Route::put('/{id}', [ManageStudentController::class, 'update'])->name('manage-students.update');
        Route::delete('/{id}', [ManageStudentController::class, 'destroy'])->name('manage-students.destroy');
        
        Route::get('/api/manage-students', [ManageStudentController::class, 'apiIndex'])->name('manage-students.api.index');
    });

    // Teacher Management Routes
    Route::prefix('manage-teachers')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('manage-teachers.index');
        Route::get('/create', [TeacherController::class, 'create'])->name('manage-teachers.create');
        Route::post('/', [TeacherController::class, 'store'])->name('manage-teachers.store');
        Route::get('/{id}', [TeacherController::class, 'show'])->name('manage-teachers.show');
        Route::get('/{id}/edit', [TeacherController::class, 'edit'])->name('manage-teachers.edit');
        Route::put('/{id}', [TeacherController::class, 'update'])->name('manage-teachers.update');
        Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('manage-teachers.destroy');
        
        Route::get('/api/manage-teachers', [TeacherController::class, 'apiIndex'])->name('manage-teachers.api.index');
    });

    // Calendar Events Routes
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('events.index');
        Route::get('/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/', [EventController::class, 'store'])->name('events.store');
        Route::get('/{id}', [EventController::class, 'show'])->name('events.show');
        Route::get('/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/{id}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/{id}', [EventController::class, 'destroy'])->name('events.destroy');
        
        Route::get('/api/events', [EventController::class, 'apiIndex'])->name('events.api.index');
    });

    // Stats API route
    Route::get('/api/stats', function () {
        $totalStaff = \App\Models\ManageStaff::count();
        $totalStudents = \App\Models\ManageStudent::count();
        $totalCourses = \App\Models\ManageStudent::distinct('course')->count('course');
        
        return response()->json([
            'success' => true,
            'data' => [
                'totalStaff' => $totalStaff,
                'totalStudents' => $totalStudents,
                'totalCourses' => $totalCourses,
                'successRate' => '96%'
            ]
        ]);
    })->name('admin.api.stats');
});

// Notification routes
Route::prefix('admin')->group(function () {
    // Notifications
    Route::get('/notifications', [AdminController::class, 'getNotifications'])->name('admin.notifications');
    Route::post('/notifications/{id}/read', [AdminController::class, 'markNotificationAsRead'])->name('admin.notifications.read');
    Route::post('/notifications/read-all', [AdminController::class, 'markAllNotificationsAsRead'])->name('admin.notifications.read-all');
    Route::get('/pending-approvals/count', [AdminController::class, 'getPendingApprovalsCount'])->name('admin.pending-approvals.count');
    Route::get('/teacher-requests', [AdminController::class, 'getTeacherRequests'])->name('admin.teacher-requests');
});


// Computer Reservation Routes
Route::prefix('admin/reservations')->group(function () {
Route::resource('computer-reservations', App\Http\Controllers\ComputerReservationController::class);

    Route::get('/', [ComputerReservationController::class, 'index']);
    Route::post('/', [ComputerReservationController::class, 'store']);
    Route::get('/{id}', [ComputerReservationController::class, 'show']);
    Route::put('/{id}', [ComputerReservationController::class, 'update']);
    Route::delete('/{id}', [ComputerReservationController::class, 'destroy']);
    
    // New approval routes - THESE ARE CORRECT
    Route::apiResource('computer-reservations', ComputerReservationController::class);
Route::post('computer-reservations/{id}/approve', [ComputerReservationController::class, 'approve']);
Route::post('computer-reservations/{id}/reject', [ComputerReservationController::class, 'reject']);
Route::get('computer-reservations/by-lab-date', [ComputerReservationController::class, 'getByLabAndDate']);
Route::get('computer-reservations/statistics', [ComputerReservationController::class, 'getStatistics']);
});



// Home Route
Route::get('/', function () {
    return view('dashboard');
})->name('home');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');