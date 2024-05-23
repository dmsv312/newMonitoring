<?php

namespace app\Models\Api;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @property int $id
 * @property float $amount
 */
class Balance extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
    ];
}
