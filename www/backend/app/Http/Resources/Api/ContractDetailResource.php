<?php

namespace App\Http\Resources\Api;

use App\Models\Api\ContractDetail;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ContractDetail
 */
class ContractDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'contractId' => $this->contract_id,
            'datetime' => $this->datetime,
            'amount' => $this->amount,
            'description' => $this->target,
            'type' => $this->getType(),
        ];
    }
}
