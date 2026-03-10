<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'location' => $this->location_id,
            'name' => $this->name,
            'description' => $this->description,
            'location' => [
                'id' => $this->location?->id,
                'name' => $this->location?->name
            ]
        ];
    }
}
