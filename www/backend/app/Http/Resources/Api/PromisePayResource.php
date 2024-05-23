<?php

namespace App\Http\Resources\Api;

use App\Models\Api\PromisePay;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PromisePay
 */
class PromisePayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $isAvailable = true;

        if (!$this->contract->is_blocked || $this->is_active) {
            $isAvailable = false;
        }

        return [
            'id' => $this->id,
            'isAvailable' => $isAvailable,
            'isActive' => (boolean)$this->is_active,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
        ];
    }
}
