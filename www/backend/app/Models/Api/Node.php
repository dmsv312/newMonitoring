<?php

namespace App\Models\Api;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @property int $id
 * @property string $project_name
 * @property string $name
 * @property string $location_name
 * @property string $location_url
 * @property string $last_block
 * @property bool $is_sync
 */
class Node extends Model
{
    use HasFactory;
}
