<?php

namespace App\Http\Controllers;

use App\Models\ManageStaff;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ManageStaffController extends Controller
{
    // Web Methods
    public function index(): View
    {
        $staff = ManageStaff::all();
        return view('manage-staff.index', compact('staff'));
    }

    public function create(): View
    {
        return view('manage-staff.create');
    }

    public function show(string $id): View
    {
        $staff = ManageStaff::findOrFail($id);
        return view('manage-staff.show', compact('staff'));
    }

    public function edit(string $id): View
    {
        $staff = ManageStaff::findOrFail($id);
        return view('manage-staff.edit', compact('staff'));
    }

    // API Methods
    public function apiIndex(): JsonResponse
    {
        try {
            $staff = ManageStaff::all();
            return response()->json(['success' => true, 'data' => $staff]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch staff'], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:manage_staff,email',
            'department' => 'required|in:BSIT,BSBA,BSHM,BEED,BSED,Admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        $staff = ManageStaff::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Staff created successfully',
                'data' => $staff
            ], 201);
        }

        return redirect()->route('manage-staff.index')->with('success', 'Staff created successfully');
    }

    public function update(Request $request, string $id)
    {
        $staff = ManageStaff::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:manage_staff,email,' . $id,
            'department' => 'required|in:BSIT,BSBA,BSHM,BEED,BSED,Admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        $staff->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Staff updated successfully',
                'data' => $staff
            ]);
        }

        return redirect()->route('manage-staff.index')->with('success', 'Staff updated successfully');
    }

    public function destroy(Request $request, string $id)
    {
        $staff = ManageStaff::findOrFail($id);
        $staff->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Staff deleted successfully']);
        }

        return redirect()->route('manage-staff.index')->with('success', 'Staff deleted successfully');
    }
}