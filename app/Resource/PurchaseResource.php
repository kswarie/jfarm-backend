<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'site_id' => $this->site_id,
            'purchase_date' => $this->purchase_date,
            'total_price' => floatval($this->total_price),
            'remarks' => $this->remarks,
            'purchase_detail' => PurchaseDetailResource::collection(
                $this->whenLoaded('details')
            )
        ];
    }
}
