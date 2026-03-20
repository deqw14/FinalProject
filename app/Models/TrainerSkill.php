<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainerSkill extends Model
{
    protected $fillable = ['trainer_id', 'skill'];

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }
}
