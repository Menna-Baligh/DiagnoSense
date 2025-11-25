<?php

namespace App\Http\Resources\Room;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'room-code'       => $this->room_code,
            'title'      => $this->title,
            'patient_id' => $this->patient_id,
            'doctor_id'  => $this->doctor_id,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
