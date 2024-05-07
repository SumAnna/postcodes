<?php

namespace Tests\Feature;

use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Enums\UserEnum;
use App\Enums\StoreEnum;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test store creation with dynamic user roles.
     *
     * @throws Exception
     */
    #[DataProvider('roleProvider')]
    public function testStoreCreationWithRole(string $role, bool $shouldBeInDatabase): void
    {
        $user = $this->createUserWithRole($role);
        $name = 'New '.ucfirst($role).' Test Store';

        $response = $this->actingAs($user)->post('/store/add', [
            'name' => $name,
            'geo_coordinates' => '10.32424,10.34234',
            'is_open' => true,
            'store_type' => StoreEnum::RESTAURANT->value,
            'max_delivery_distance' => 10.5,
        ]);

        if ($shouldBeInDatabase) {
            $response->assertStatus(201);
            $this->assertDatabaseHas('stores', ['name' => $name, 'user_id' => $user->id]);
        } else {
            $response->assertStatus(302);
            $this->assertDatabaseMissing('stores', ['name' => $name, 'user_id' => $user->id]);
        }
    }

    /**
     * Get expected outcomes for different user roles.
     */
    public static function roleProvider(): array
    {
        return [
            [UserEnum::USER->value, false],
            [UserEnum::ADMIN->value, true],
            [UserEnum::MODERATOR->value, true],
        ];
    }

    /**
     * Create a user with a specific role.
     *
     * @throws Exception
     */
    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create([
            'role' => $role,
            'name' => 'Test '.ucfirst($role).' User',
        ]);

        if (!$user->exists) {
            throw new Exception("User could not be created.");
        }

        return $user;
    }
}
