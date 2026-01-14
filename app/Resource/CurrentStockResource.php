<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrentStockResource extends JsonResource
{
    public function toArray($request) {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'qty' => floatval($this->qty),
            'uom' => $this->uom,
        ];
    }
}
