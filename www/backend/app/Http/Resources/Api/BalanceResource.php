<?php

namespace App\Http\Resources\Api;

use App\Models\Api\Balance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Balance
 */
class BalanceResource extends JsonResource
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
            'contractId' => $this->contract_id,
            'isBlocked' => (bool)$this->contract->is_blocked,
            'amount' => $this->amount,
            'lastReplenishmentDate' => date('c', strtotime($this->lastReplenishment?->datetime)),
            'blockingDate' => $this->blocking_date,
            'recommendedPaymentAmount' => $this->recommended_payment_amount,
            'minimumPaymentAmount' => $this->minimum_payment_amount,
            'createdAt' => $this->created_at,
        ];
    }
}
