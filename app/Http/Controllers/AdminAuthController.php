<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminPlain;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('adminform');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Manually check for plain text passwords
        $admin = AdminPlain::where('email', $request->email)->first();

        if ($admin && $admin->password === $request->password) {
            // Manually log in the user
            Auth::login($admin);
            
            return redirect()->route('admin.dashboard')
                ->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'Invalid login credentials.',
        ]); 
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}