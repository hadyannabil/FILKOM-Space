<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'user_id',
        'room_id',
        'event_name',
        'pic_name',
        'pic_email',
        'attendees',
        'notes',
        'reservation_date',
        'start_time',
        'end_time',
        'approval_letter',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'reviewed_at'      => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public static function generateRequestId(): string
    {
        $year = now()->year;
        $last = static::whereYear('created_at', $year)
                    ->orderByDesc('id')
                    ->value('request_id');

        if ($last && preg_match('/#REQ-\d+-(\d+)/', $last, $m)) {
            $next = (int) $m[1] + 1;
        } else {
            $next = 1;
        }

        return sprintf('#REQ-%d-%04d', $year, $next);
    }
}
