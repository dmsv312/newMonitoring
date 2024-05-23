<?php

namespace app\Models\Api;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @property int $id
 * @property string $address
 * @property string $symbol
 */
class Token extends Model
{
    use HasFactory;
}
