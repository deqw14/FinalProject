<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'plan_name'          => $this->plan_name,
            'price'              => (float) $this->price,
            'sessions_total'     => $this->sessions_total,
            'sessions_used'      => $this->sessions_used,
            'sessions_remaining' => $this->sessions_remaining,
            'starts_at'          => $this->starts_at->toDateString(),
            'expires_at'         => $this->expires_at->toDateString(),
            'status'             => $this->status,
            'is_active'          => $this->isActive(),
        ];
    }
}
