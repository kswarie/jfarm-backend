<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class BirthDeathResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'site_id' => $this->site_id,
            'cage_id' => $this->cage_id,
            'input_date' => $this->input_date,
            'type' => $this->type,
            'quantity' => intval($this->quantity),
            'cause' => $this->cause,
            'source' => $this->source,
            'remarks' => $this->remarks

        ];
    }
}
