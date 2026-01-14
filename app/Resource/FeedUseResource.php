<?php

namespace App\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedUseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'site_id' => $this->site_id,
            'use_date' => $this->use_date,
            'feed_name' => $this->feed_name,
            'qty' => floatval($this->qty),
            'remarks' => $this->remarks
        ];
    }
}
