<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_name',
        'price',
        'sessions_total',
        'sessions_used',
        'starts_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sessions_total' => 'integer',
        'sessions_used' => 'integer',
        'starts_at' => 'date',
        'expires_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSessionsRemainingAttribute(): int
    {
        return max(0, $this->sessions_total - $this->sessions_used);
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->expires_at->isFuture()
            && $this->sessions_remaining > 0;
    }
}
