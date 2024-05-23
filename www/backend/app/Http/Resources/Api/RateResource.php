<?php

namespace App\Http\Resources\Api;

use App\Models\Api\Rate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
* @mixin Rate
 */
class RateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'speed' => $this->speed,
            'channels' => $this->channels,
            'hdChannels' => $this->hd_channels,
            'price' => ceil($this->price),
        ];
    }
}
