<?php

namespace App\Http\Resources\Society;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitorEntryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'visitor' => new VisitorResource($this->whenLoaded('visitor')),
            'visitor_type' => $this->visitorType ? $this->visitorType->name : null,
            'unit_number' => $this->unit ? $this->unit->unit_number : null,
            'resident_name' => $this->resident ? $this->resident->name : null,
            'purpose' => $this->purpose,
            'entry_time' => $this->entry_time ? $this->entry_time->toIso8601String() : null,
            'exit_time' => $this->exit_time ? $this->exit_time->toIso8601String() : null,
            'status' => $this->status,
            'otp' => $this->otp, // Should probably be hidden in some cases, but keeping for now
            'qr_code' => $this->qr_code,
            'notes' => $this->notes,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
