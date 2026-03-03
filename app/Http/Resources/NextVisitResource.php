<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NextVisitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'next_visit_date' => $this->next_visit_date ? $this->next_visit_date->format('Y-m-d') : 'No next visit',
            'status' => $this->status,
        ];
    }
}
