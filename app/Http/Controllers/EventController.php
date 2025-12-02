<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EventController extends Controller
{
    // Web Methods
    public function index(): View
    {
        $events = Event::orderBy('event_date', 'asc')
                      ->orderBy('event_time', 'asc')
                      ->get();
        return view('events.index', compact('events'));
    }

    public function create(): View
    {
        return view('events.create');
    }

    public function show(string $id): View
    {
        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function edit(string $id): View
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    // API Methods
    public function apiIndex(): JsonResponse
    {
        try {
            $events = Event::orderBy('event_date', 'asc')
                          ->orderBy('event_time', 'asc')
                          ->get();
            return response()->json(['success' => true, 'data' => $events]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch events'], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required', // Fixed: removed date_format:H:i
            'event_type' => 'required|in:academic,personal,holiday,meeting'
        ]);

        $event = Event::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Event created successfully',
                'data' => $event
            ], 201);
        }

        return redirect()->route('events.index')->with('success', 'Event created successfully');
    }

    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'event_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'required', // Fixed: removed date_format:H:i
            'event_type' => 'required|in:academic,personal,holiday,meeting'
        ]);

        $event->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully',
                'data' => $event
            ]);
        }

        return redirect()->route('events.index')->with('success', 'Event updated successfully');
    }

    public function destroy(Request $request, string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Event deleted successfully']);
        }

        return redirect()->route('events.index')->with('success', 'Event deleted successfully');
    }
}