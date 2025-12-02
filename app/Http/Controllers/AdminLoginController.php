<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminPlain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('adminform');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Find admin by email
        $admin = AdminPlain::where('email', $request->email)->first();

        // Check if admin exists and password matches (plain text comparison)
        if ($admin && $admin->password === $request->password) {
            // Login successful - redirect to MainDashboard
            return redirect('/admin/dashboard');
        }

        // Login failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        return redirect('/admin/login');
    }
}