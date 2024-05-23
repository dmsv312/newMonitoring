<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archive_nodes', function (Blueprint $table) {
            $table->string('public_rpc_url')->nullable()->after('url');
            $table->bigInteger('real_block')->nullable()->after('last_block');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archive_nodes', function (Blueprint $table) {
            $table->dropColumn('public_rpc_url');
            $table->dropColumn('real_block');
        });
    }
};
