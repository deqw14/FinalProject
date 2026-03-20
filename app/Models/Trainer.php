<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id',
        'name',
        'description',
        'photo',
        'specialization',
        'rating',
        'max_clients',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'max_clients' => 'integer',
    ];

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(TrainerSkill::class);
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getClientCountAttribute(): int
    {
        return $this->bookings()
            ->where('status', '!=', 'cancelled')
            ->distinct('user_id')
            ->count('user_id');
    }

    public function getFreeSlotsAttribute(): int
    {
        $bookedToday = $this->bookings()
            ->where('booking_date', now()->toDateString())
            ->where('status', '!=', 'cancelled')
            ->count();

        return max(0, $this->max_clients - $bookedToday);
    }

    public function updateRating(): void
    {
        $avgRating = $this->reviews()->avg('rating') ?? 0;
        $this->update(['rating' => round($avgRating, 2)]);
    }
}
