<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'goal',
        'experience',
        'frequency_per_week',
        'trainer_style',
    ];

    protected $casts = [
        'frequency_per_week' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
