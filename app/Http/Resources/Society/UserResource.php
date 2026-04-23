<?php

namespace App\Http\Resources\Society;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'unit' => $this->unit_number ?? 'N/A',
            'status' => $this->is_approved ? 'Approved' : 'Pending',
            'registered_at' => $this->created_at->format('d M Y'),
        ];
    }
}
