<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddIndexesToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->index('lat', 'idx_lat');
            $table->index('long', 'idx_long');
            $table->index('is_open', 'idx_open');
            $table->index('max_delivery_distance', 'idx_distance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropIndex('idx_lat');
            $table->dropIndex('idx_long');
            $table->dropIndex('idx_open');
            $table->dropIndex('idx_distance');
        });
    }
}
