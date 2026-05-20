<?php

namespace App\Http\Resources\Society;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'vehicle_number' => $this->vehicle_number,
            'photo_url' => $this->photo_path ? asset('storage/' . $this->photo_path) : null,
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
