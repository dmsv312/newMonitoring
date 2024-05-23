<?php

namespace App\Models\Api;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $password
 * @property int $space_total
 * @property int $space_free
 * @property int $cpu_usage
 * @property float $ram_total
 * @property float $ram_free
 */
class Server extends Model
{
    use HasFactory;
}
