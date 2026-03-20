<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'gym'              => new GymResource($this->whenLoaded('gym')),
            'booking'          => new BookingResource($this->whenLoaded('booking')),
            'checked_in_at'    => $this->checked_in_at->toIso8601String(),
            'checked_out_at'   => $this->checked_out_at?->toIso8601String(),
            'duration_minutes' => $this->duration_minutes,
        ];
    }
}
