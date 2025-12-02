<?php

namespace App\Http\Controllers;

use App\Models\ComputerReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ComputerReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = ComputerReservation::latest()->get();
        
        return response()->json([
            'success' => true,
            'data' => $reservations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * This now only creates a PENDING reservation
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'lab' => 'required|string|in:Computer Laboratory 1,Computer Laboratory 2,Computer Laboratory Netlab',
            'purpose' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'requester' => 'required|string|max:255',
            'participants' => 'nullable|integer|min:1',
            'requirements' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $reservationData = $validator->validated();
        $reservationData['status'] = 'pending';
        $reservationData['submitted_at'] = now();

        $reservation = ComputerReservation::create($reservationData);

        return response()->json([
            'success' => true,
            'message' => 'Reservation submitted for approval!',
            'data' => $reservation
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $reservation = ComputerReservation::find($id);

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $reservation
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reservation = ComputerReservation::find($id);

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);
        }

        // Only allow deletion of pending reservations
        if (!$reservation->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending reservations can be deleted'
            ], 422);
        }

        $reservation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reservation deleted successfully!'
        ]);
    }

    /**
     * Approve a reservation
     */
    public function approve(Request $request, string $id)
    {
        $reservation = ComputerReservation::find($id);

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);
        }

        if (!$reservation->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending reservations can be approved'
            ], 422);
        }

        // Check for time conflicts with other APPROVED reservations
        $conflictingReservation = ComputerReservation::where('lab', $reservation->lab)
            ->where('date', $reservation->date)
            ->where('id', '!=', $id)
            ->where('status', 'approved')
            ->where(function($query) use ($reservation) {
                $query->whereBetween('start_time', [$reservation->start_time, $reservation->end_time])
                      ->orWhereBetween('end_time', [$reservation->start_time, $reservation->end_time])
                      ->orWhere(function($q) use ($reservation) {
                          $q->where('start_time', '<=', $reservation->start_time)
                            ->where('end_time', '>=', $reservation->end_time);
                      });
            })
            ->first();

        if ($conflictingReservation) {
            return response()->json([
                'success' => false,
                'message' => 'Time conflict: The laboratory is already reserved by an approved reservation during this time slot.'
            ], 409);
        }

        $reservation->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::user()->name ?? 'Administrator', // Use actual user name
            'admin_notes' => $request->admin_notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reservation approved successfully!',
            'data' => $reservation
        ]);
    }

    /**
     * Reject a reservation
     */
    public function reject(Request $request, string $id)
    {
        $reservation = ComputerReservation::find($id);

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);
        }

        if (!$reservation->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending reservations can be rejected'
            ], 422);
        }

        $reservation->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::user()->name ?? 'Administrator', // Use actual user name
            'admin_notes' => $request->admin_notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reservation rejected!',
            'data' => $reservation
        ]);
    }

    /**
     * Get reservations by lab and date (only approved ones for calendar)
     */
    public function getByLabAndDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lab' => 'required|string|in:Computer Laboratory 1,Computer Laboratory 2,Computer Laboratory Netlab',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Only get approved reservations for calendar display
        $reservations = ComputerReservation::where('lab', $request->lab)
            ->where('date', $request->date)
            ->where('status', 'approved') // Only show approved ones
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reservations
        ]);
    }

    /**
     * Get reservation statistics
     */
    public function getStatistics()
    {
        $total = ComputerReservation::count();
        $pending = ComputerReservation::pending()->count();
        $approved = ComputerReservation::approved()->count();
        $rejected = ComputerReservation::rejected()->count();
        $completed = ComputerReservation::completed()->count();

        $labStats = ComputerReservation::selectRaw('lab, COUNT(*) as count')
            ->groupBy('lab')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'pending' => $pending,
                'approved' => $approved,
                'rejected' => $rejected,
                'completed' => $completed,
                'lab_stats' => $labStats,
            ]
        ]);
    }
}