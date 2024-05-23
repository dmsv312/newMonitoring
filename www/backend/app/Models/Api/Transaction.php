<?php

namespace App\Models\Api;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @property int $id
 * @property int $token_id_from
 * @property int $token_id_to
 * @property float $amount
 * @property float $fee
 * @property string $hash
 * @property boolean $is_complete
 */
class Transaction extends Model
{
    use HasFactory;

    public const ETH_AMOUNT = [0.01, 0.02, 0.03, 0.04, 0.05];
    public const TOKEN_IDS = [2, 3];

    protected $fillable = [
        'token_id_from',
        'token_id_to',
        'amount',
        'fee',
        'hash',
        'is_complete'
    ];

    public function createTransaction(): bool
    {
        /** @var Transaction $transaction */
        $transaction = Transaction::orderBy('id', 'DESC')->first();

        // create new Transaction item with is_complete = false and without hash;
        $nextTransaction = new Transaction();
        // Next transaction from Stable to ETH
        if ($transaction->token_id_from == 1) {
            $nextTransaction->token_id_from = $transaction->token_id_to;
            $nextTransaction->token_id_to = 1;
            // TODO - how to update balance in tokens
            $nextTransaction->amount = 1;
        }
        // Next transaction from ETH to USDT
        else {
            $nextTransaction->token_id_from = 1;
            $randomToken = self::TOKEN_IDS[array_rand(self::TOKEN_IDS)];
            $nextTransaction->token_id_to = $randomToken;
            $randomAmount = self::ETH_AMOUNT[array_rand(self::ETH_AMOUNT)];
            $nextTransaction->amount = $randomAmount;
        }

        $nextTransaction->is_complete = false;
        return $nextTransaction->save();
    }
}
