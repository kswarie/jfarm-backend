<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesResource extends JsonResource
{
    public function toArray($request) {
        return [
            'id' => $this->id,
            'site_id' => $this->site_id,
            'sales_date' => $this->sales_date,
            'total_price' => floatval($this->total_price),
            'remarks' => $this->remarks,
            'sales_detail' => SalesDetailResource::collection(
                $this->whenLoaded('details')
            )
        ];
    }
}
