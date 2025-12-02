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
        'admin_notes',
        'reviewed_at',
        'reviewed_by'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'string', // changed from datetime → string
        'end_time' => 'string',   // changed from datetime → string
        'participants' => 'integer',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // === SCOPES ===
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByLab($query, $lab)
    {
        return $query->where('lab', $lab);
    }

    public function scopeDateRange($query, $startDate, $endDate = null)
    {
        $endDate = $endDate ?: $startDate;
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    // === STATUS CHECKERS ===
    public function isPending() { return $this->status === 'pending'; }
    public function isApproved() { return $this->status === 'approved'; }
    public function isRejected() { return $this->status === 'rejected'; }
    public function isCompleted() { return $this->status === 'completed'; }

    // === ACCESSORS ===
    public function getTimeRangeAttribute()
    {
        return $this->start_time . ' - ' . $this->end_time;
    }

    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('D, M j, Y') : null;
    }
}
