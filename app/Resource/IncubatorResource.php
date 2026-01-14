<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class IncubatorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'status' => $this->status

        ];
    }
}
