<?php

namespace App\Http\Controllers;

use App\Models\ManageStudent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ManageStudentController extends Controller
{
    // Web Methods
    public function index(): View
    {
        $students = ManageStudent::all();
        return view('manage-students.index', compact('students'));
    }

    public function create(): View
    {
        return view('manage-students.create');
    }

    public function show(string $id): View
    {
        $student = ManageStudent::findOrFail($id);
        return view('manage-students.show', compact('student'));
    }

    public function edit(string $id): View
    {
        $student = ManageStudent::findOrFail($id);
        return view('manage-students.edit', compact('student'));
    }

    // API Methods
    public function apiIndex(): JsonResponse
    {
        try {
            $students = ManageStudent::all();
            return response()->json(['success' => true, 'data' => $students]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch students'], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|unique:manage_students,student_id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:manage_students,email',
            'course' => 'required|in:BSIT,BSBA,BSHM,BEED,BSED',
            'section' => 'required|string|max:50',
            'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        $student = ManageStudent::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'data' => $student
            ], 201);
        }

        return redirect()->route('manage-students.index')->with('success', 'Student created successfully');
    }

    public function update(Request $request, string $id)
    {
        $student = ManageStudent::findOrFail($id);

        $validated = $request->validate([
            'student_id' => 'required|string|unique:manage_students,student_id,' . $id,
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:manage_students,email,' . $id,
            'course' => 'required|in:BSIT,BSBA,BSHM,BEED,BSED',
            'section' => 'required|string|max:50',
            'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        $student->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully',
                'data' => $student
            ]);
        }

        return redirect()->route('manage-students.index')->with('success', 'Student updated successfully');
    }

    public function destroy(Request $request, string $id)
    {
        $student = ManageStudent::findOrFail($id);
        $student->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Student deleted successfully']);
        }

        return redirect()->route('manage-students.index')->with('success', 'Student deleted successfully');
    }
}