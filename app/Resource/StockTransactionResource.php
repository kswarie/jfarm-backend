<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class StockTransactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'stock_id' => $this->stock_id,
            'type' => $this->type,
            'qty' => $this->qty,
            'reff' => $this->reff,
            'stock' => [
                'id' => $this->stock?->id,
                'product_id' => $this->stock?->product_id,
                'register_date' => $this->stock?->register_date->format('Y-m-d H:i:s'),
                'days_old' => $this->stock?->days_old,
                'qty' => $this->stock?->qty,
                'uom' => $this->stock?->uom,
                'product' => [
                    'id' => $this->stock?->product?->id,
                    'code' => $this->stock?->product?->code,
                    'name' => $this->stock?->product?->name,
                ]
            ]
        ];
    }
}
