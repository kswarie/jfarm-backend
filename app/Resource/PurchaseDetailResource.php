<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'purchase_id' => $this->purchase_id,
            'quantity' => floatval($this->quantity),
            'uom' => $this->uom,
            'unit_price' => floatval($this->unit_price),
            'total_price' => floatval($this->total_price),
            'product' => [
                'id' => $this->product?->id,
                'code' => $this->product?->code,
                'name' => $this->product?->name

            ]

        ];
    }
}
