<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'sales_id' => $this->sales_id,
            'quantity' => floatval($this->quantity),
            'uom' => $this->uom,
            'unit_price' => floatval($this->unit_price),
            'total_price' => floatval($this->total_price),
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
