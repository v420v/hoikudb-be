<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreschoolResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "type" => "Feature",
            'properties' => [
                'id' => $this->id,
                'name' => $this->name,
                'stats' => PreschoolStatResource::collection($this->whenLoaded('preschoolStats')),
            ],
            'geometry' => PreschoolLocationResource::make($this->whenLoaded('preschoolLocation')),
        ];
    }
}
