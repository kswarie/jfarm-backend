<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class IncubationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'site_id' => $this->site_id,
            'incubator_id' => $this->incubator_id,
            'tray_id' => $this->tray_id,
            'batch_code' => $this->batch_code,
            'start_date' => $this->start_date,
            'hatch_date' => $this->hatch_date,
            'egg_qty' => intval($this->egg_qty),
            'hatch_qty' => intval($this->hatch_qty),
            'damage_qty' => intval($this->damage_qty),
            'remarks' => $this->remarks

        ];
    }
}
