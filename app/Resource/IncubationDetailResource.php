<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class IncubationDetailResource extends JsonResource
{
    public function toArray($request) {
        return [
            'id' => $this->id,
            'incubation_id' => $this->incubation_id,
            'egg_qty' => intval($this->egg_qty),
            'hatch_qty' => intval($this->hatch_qty),
            'damage_qty' => intval($this->damage_qty),
            'remarks' => $this->remarks,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
