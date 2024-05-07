<?php

namespace Database\Factories;

use App\Enums\StoreEnum;
use App\Enums\UserEnum;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     *
     * @throws Exception
     */
    public function definition(): array
    {
        $firstUserId = User::getFirstAdminOrModerator();

        if (null === $firstUserId) {
            throw new Exception("No users with 'admin' or 'moderator' role found. Cannot create store.");
        }

        return [
            'name' => $this->faker->company,
            'lat' => $this->faker->latitude(50, 60), // Define regex pattern for UK lat (roughly)
            'long' => $this->faker->longitude(-8, 2), // Define regex pattern for UK long (roughly)
            'is_open' => $this->faker->boolean,
            'store_type' => $this->faker->randomElement(StoreEnum::cases() ?? []),
            'max_delivery_distance' => $this->faker->numberBetween(10, 50),
            'user_id' => $firstUserId,
        ];
    }
}

