<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'booking_date'     => $this->booking_date->toDateString(),
            'start_time'       => $this->start_time,
            'end_time'         => $this->end_time,
            'duration_minutes' => $this->duration_minutes,
            'price'            => (float) $this->price,
            'status'           => $this->status,
            'trainer'          => new TrainerResource($this->whenLoaded('trainer')),
            'can_cancel'       => $this->canBeCancelled(),
            'has_review'       => $this->whenLoaded('review', fn () => $this->review !== null),
            'cancelled_at'     => $this->cancelled_at?->toIso8601String(),
            'created_at'       => $this->created_at->toIso8601String(),
        ];
    }
}
