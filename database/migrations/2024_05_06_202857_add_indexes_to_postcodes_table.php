<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('postcodes', function (Blueprint $table) {
            $table->index(['lat', 'long'], 'idx_lat_long');
            $table->index('pcd', 'idx_pcd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postcodes', function (Blueprint $table) {
            $table->dropIndex('idx_lat_long');
        });
    }
};
