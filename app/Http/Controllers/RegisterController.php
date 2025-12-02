<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('adminregister'); // this points to resources/views/register.blade.php
    }
    
public function register(Request $request)
{
    // Validate and save admin registration
    // Example:
    $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:admins,email',
        'password' => 'required|min:6',
    ]);
   // Save admin logic...
    }
}
