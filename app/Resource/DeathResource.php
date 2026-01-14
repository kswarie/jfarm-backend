<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class DeathResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'site_id' => $this->site_id,
            'stock_id' => $this->stock_id,
            'input_date' => $this->input_date,
            'quantity' => intval($this->quantity),
            'remarks' => $this->remarks,
            'stock' => [
                'register_date' => $this->stock?->register_date,
                'dasy_old' => $this->stock?->dasy_old
            ],
            'product' => [
                'code' => $this->stock?->product?->code,
                'name' => $this->stock?->product?->name,
            ]
        ];
    }
}
