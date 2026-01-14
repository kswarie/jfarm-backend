<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class TrayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'description' => $this->description,
            'status' => $this->status,

        ];
    }
}
