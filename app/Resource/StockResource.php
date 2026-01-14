<?php

namespace App\Resource;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    static function ages($reg_date, $day_old)
    {
        $diff = Carbon::parse($reg_date)->diffInDays(Carbon::today());
        return $diff + $day_old;
    }
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'site_id' => $this->site_id,
            'product_id' => $this->product_id,
            'register_date' => $this->register_date,
            'days_old' => $this->days_old,
            'ages' => $this->ages($this->register_date, $this->days_old) ,
            'qty' => floatval($this->qty),
            'uom' => $this->uom,
            'remarks' => $this->remarks,
            'status' => $this->status,
            'product' => [
                'id' => $this->product?->id,
                'code' => $this->product?->code,
                'name' => $this->product?->name,
                'status' => $this->product?->status
            ]
        ];
    }
}
