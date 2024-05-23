<?php

namespace App\Http\Resources\Api;

use App\Models\Api\ContractService;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ContractService
 */
class ContractServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'serviceId' => $this->service_id,
            'contractId' => $this->contract_id,
            'name' => $this->service->name,
            'description' => $this->service->description,
            'updatedAt' => $this->updated_at,
            'isActive' => $this->status == 1,
        ];
    }
}
