<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gym extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'address',
        'max_capacity',
        'current_people',
    ];

    protected $casts = [
        'max_capacity' => 'integer',
        'current_people' => 'integer',
    ];

    public function trainers(): HasMany
    {
        return $this->hasMany(Trainer::class);
    }

    public function visitHistory(): HasMany
    {
        return $this->hasMany(VisitHistory::class);
    }

    public function getOccupancyPercentageAttribute(): float
    {
        if ($this->max_capacity === 0) {
            return 0.0;
        }

        return round(($this->current_people / $this->max_capacity) * 100, 1);
    }
}
