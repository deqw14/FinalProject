<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trainer_id',
        'time_slot_id',
        'booking_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'price',
        'status',
        'cancelled_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TimeSlot::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function getStartsAtAttribute(): Carbon
    {
        return Carbon::parse($this->booking_date->format('Y-m-d') . ' ' . $this->start_time);
    }

    public function canBeCancelled(): bool
    {
        return $this->status === 'confirmed'
            && $this->starts_at->diffInMinutes(now(), false) < -60;
    }
}
