<?php

use App\Enums\StoreEnum;
use App\Helpers\EnumHelper;
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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('lat', 10, 8);
            $table->decimal('long', 11, 8);
            $table->boolean('is_open');
            $table->enum('store_type',
                EnumHelper::getEnumValues(StoreEnum::cases() ?? [])
            );
            $table->double('max_delivery_distance');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
