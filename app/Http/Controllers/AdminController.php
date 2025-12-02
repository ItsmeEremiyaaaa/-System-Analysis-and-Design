<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\Notification;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function getTeacherRequests()
    {
        $pendingEvents = Event::where('status', 'pending')
            ->with('teacher')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingReservations = Reservation::where('status', 'pending')
            ->with('teacher')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'events' => $pendingEvents,
            'reservations' => $pendingReservations
        ]);
    }

    public function approveEvent(Request $request, $id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            // Create notification for teacher
            Notification::create([
                'user_id' => $event->teacher_id,
                'title' => 'Class Approved',
                'message' => 'Your class "' . $event->title . '" has been approved by admin.',
                'type' => 'class_approved',
                'related_id' => $event->id,
                'related_type' => 'event'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Event approved successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving event: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectEvent(Request $request, $id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->update([
                'status' => 'rejected',
                'admin_feedback' => $request->feedback,
                'rejected_by' => Auth::id(),
                'rejected_at' => now()
            ]);

            // Create notification for teacher
            Notification::create([
                'user_id' => $event->teacher_id,
                'title' => 'Class Rejected',
                'message' => 'Your class "' . $event->title . '" has been rejected. Feedback: ' . $request->feedback,
                'type' => 'class_rejected',
                'related_id' => $event->id,
                'related_type' => 'event'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Event rejected successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting event: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approveReservation(Request $request, $id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            // Create notification for teacher
            Notification::create([
                'user_id' => $reservation->teacher_id,
                'title' => 'Reservation Approved',
                'message' => 'Your reservation for "' . $reservation->lab . '" has been approved.',
                'type' => 'reservation_approved',
                'related_id' => $reservation->id,
                'related_type' => 'reservation'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reservation approved successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving reservation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectReservation(Request $request, $id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->update([
                'status' => 'rejected',
                'admin_feedback' => $request->feedback,
                'rejected_by' => Auth::id(),
                'rejected_at' => now()
            ]);

            // Create notification for teacher
            Notification::create([
                'user_id' => $reservation->teacher_id,
                'title' => 'Reservation Rejected',
                'message' => 'Your reservation for "' . $reservation->lab . '" has been rejected. Feedback: ' . $request->feedback,
                'type' => 'reservation_rejected',
                'related_id' => $reservation->id,
                'related_type' => 'reservation'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reservation rejected successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting reservation: ' . $e->getMessage()
            ], 500);
        }
    }
        public function getNotifications()
    {
        $user = Auth::user();
        
        $notifications = Notification::where('user_id', $user->id)
            ->where('read', false)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'related_id' => $notification->related_id,
                    'related_type' => $notification->related_type,
                    'time_ago' => $notification->created_at->diffForHumans(),
                    'created_at' => $notification->created_at->format('M j, Y g:i A')
                ];
            });

        $pendingEventsCount = Event::where('status', 'pending')->count();
        $pendingReservationsCount = Reservation::where('status', 'pending')->count();

        return response()->json([
            'notifications' => $notifications,
            'counts' => [
                'pending_events' => $pendingEventsCount,
                'pending_reservations' => $pendingReservationsCount,
                'total' => $notifications->count()
            ]
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->update([
                'read' => true,
                'read_at' => now()
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notification as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        try {
            $user = Auth::user();
            
            Notification::where('user_id', $user->id)
                ->where('read', false)
                ->update([
                    'read' => true,
                    'read_at' => now()
                ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending approvals count
     */
    public function getPendingApprovalsCount()
    {
        $pendingEvents = Event::where('status', 'pending')->count();
        $pendingReservations = Reservation::where('status', 'pending')->count();

        return response()->json([
            'success' => true,
            'counts' => [
                'pending_events' => $pendingEvents,
                'pending_reservations' => $pendingReservations,
                'total' => $pendingEvents + $pendingReservations
            ]
        ]);
    }

    /**
     * Get teacher requests (for pending approvals modal)
     */
    public function getTeacherRequests()
    {
        $pendingEvents = Event::where('status', 'pending')
            ->with('teacher')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingReservations = Reservation::where('status', 'pending')
            ->with('teacher')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'events' => $pendingEvents,
            'reservations' => $pendingReservations
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}