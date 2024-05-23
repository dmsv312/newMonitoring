<?php

namespace app\Models\Api;

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
 * @property bool $is_complete
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'token_id_from',
        'token_id_to',
        'amount',
        'fee',
        'hash',
        'is_complete'
    ];
}
