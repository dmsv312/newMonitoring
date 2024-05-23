<?php

namespace App\Models\Api;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @property string $location
 * @property string $url
 * @property string $public_rpc_url
 * @property int $last_block
 * @property int $previous_block
 * @property int $real_block
 * @property bool $is_sync
 */
class ArchiveNode extends Model
{
    use HasFactory;
}
