<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->patient->id,
            'name' => $this->name,
            'age' => $this->patient->age,
            'status' => $this->patient->status,
            'ai_insight' => $this->patient->latestAiAnalysisResult->ai_insight ?? 'No analysis available yet',
            'last_visit' => $this->patient->created_at->format('M d, Y'),
            'next_appointment' => 'Feb 2,2026',
        ];
    }
}
