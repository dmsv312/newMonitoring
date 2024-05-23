<?php

namespace App\Models\Api;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @property int $id
 * @property string $ip
 * @property string $location
 * @property string $address
 * @property int $previous_reputation
 * @property int $current_reputation
 * @property int $rank
 * @property bool $is_sync
 */
class Exorde extends Model
{
    use HasFactory;
}
