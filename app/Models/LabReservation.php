<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComputerReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'lab', 
        'purpose',
        'date',
        'start_time',
        'end_time',
        'requester',
        'participants',
        'requirements',
        'status',
        'submitted_at',
        'reviewed_at',      // ADD THIS
        'reviewed_by',      // ADD THIS
        'admin_notes'       // ADD THIS
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'participants' => 'integer',
        'reviewed_at' => 'datetime', // ADD THIS
    ];

    /**
     * Scope for pending reservations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved reservations
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected reservations
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for completed reservations
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for reservations by lab
     */
    public function scopeByLab($query, $lab)
    {
        return $query->where('lab', $lab);
    }

    /**
     * Scope for reservations by date range
     */
    public function scopeDateRange($query, $startDate, $endDate = null)
    {
        $endDate = $endDate ?: $startDate;
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Check if reservation is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if reservation is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if reservation is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if reservation is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute()
    {
        return $this->start_time . ' - ' . $this->end_time;
    }

    /**
     * Get formatted date with day
     */
    public function getFormattedDateAttribute()
    {
        return $this->date->format('D, M j, Y');
    }
}