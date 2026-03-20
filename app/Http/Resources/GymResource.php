<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GymResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'city'                 => $this->city,
            'address'              => $this->address,
            'max_capacity'         => $this->max_capacity,
            'current_people'       => $this->current_people,
            'occupancy_percentage' => $this->occupancy_percentage,
        ];
    }
}
