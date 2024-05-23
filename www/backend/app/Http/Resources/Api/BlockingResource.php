<?php

namespace App\Http\Resources\Api;

use App\Models\Api\Blocking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Blocking
 */
class BlockingResource extends JsonResource
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
            'isActive' => (boolean)$this->is_active,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
        ];
    }
}
