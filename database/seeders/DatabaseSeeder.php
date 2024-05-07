<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        for ($i = 0; $i < 10; $i++) {
            try {
                Store::factory()->create();
            } catch (Exception $e) {
                Log::error(sprintf("Error occurred when trying to seed the DB: %s", $e->getMessage()));

                continue;
            }
        }
    }
}
