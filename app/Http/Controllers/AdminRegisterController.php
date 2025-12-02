<?php

namespace App\Http\Controllers;

use App\Models\AdminPlain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('adminregister');
    }

    public function register(Request $request)
    {
        // Validation
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Handle profile photo upload
            $profilePhotoPath = null;
            if ($request->hasFile('profile_photo')) {
                $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            // Create admin user
            $admin = AdminPlain::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => $request->password, // Stored as plain text based on your model
                'profile_photo' => $profilePhotoPath
            ]);

            return redirect()->route('admin.register')
                ->with('success', 'Admin account created successfully! You can now login.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create admin account: ' . $e->getMessage())
                ->withInput();
        }
    }
}