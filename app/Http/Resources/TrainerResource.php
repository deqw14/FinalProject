<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'photo'          => $this->photo,
            'specialization' => $this->specialization,
            'rating'         => (float) $this->rating,
            'skills'         => $this->whenLoaded('skills', fn () => $this->skills->pluck('skill')),
            'gym'            => new GymResource($this->whenLoaded('gym')),
            'client_count'   => $this->client_count,
            'free_slots'     => $this->free_slots,
            'reviews_count'  => $this->whenLoaded('reviews', fn () => $this->reviews->count()),
            'reviews'        => ReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}
