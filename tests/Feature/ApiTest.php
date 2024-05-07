<?php

namespace Tests\Feature;

use App\Enums\StoreEnum;
use App\Enums\UserEnum;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Postcode;
use App\Models\Store;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    private $adminUser;
    private $testPostcode = 'M27 9UZ';
    private $testLat = 53.52470677131598;
    private $testLong = -2.3480302301777467;
    private $testStoreLat = 53.52277800029829;
    private $testStoreLong = -2.3504431850920025;

    private $testStoreName = 'Test Store';

    private $storesAPI = '/api/stores/';

    /**
     * Set up the test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Create admin user
        $this->adminUser = User::factory()->create([
            'role' => UserEnum::ADMIN,
            'name' => 'Test Admin User',
        ]);
    }

    /**
     * Create test postcode.
     */
    private function createPostcode(string $postcode, float $lat, float $long, float $maxDeliveryDistance = null)
    {
        return Postcode::create([
            'pcd' => $postcode,
            'lat' => $lat,
            'long' => $long,
            'max_delivery_distance' => $maxDeliveryDistance,
        ]);
    }

    /**
     * Create test store.
     */
    private function createStore(string $name, float $lat, float $long, bool $isOpen, float $maxDeliveryDistance)
    {
       return Store::create([
            'name' => $name,
            'lat' => $lat,
            'long' => $long,
            'store_type' => StoreEnum::RESTAURANT,
            'is_open' => $isOpen,
            'max_delivery_distance' => $maxDeliveryDistance,
            'user_id' => $this->adminUser->id,
        ]);
    }

    /**
     * Test invalid postcode.
     */
    public function testInvalidPostcode(): void
    {
        $response = $this->json('POST', $this->storesAPI.'999');
        $response->assertStatus(422)
            ->assertJson(['message' => 'The postcode must be a valid UK postcode.']);
    }

    /**
     * Test API that returns stores.
     */
    public function testNoStores(): void
    {
        $this->createPostcode($this->testPostcode, $this->testLat, $this->testLong);
        $response = $this->json('POST', $this->storesAPI.$this->testPostcode);
        $response->assertOk()->assertJson([]);
    }

    /**
     * Test API that returns found stores.
     */
    public function testFoundStores(): void
    {
        $this->createPostcode($this->testPostcode, $this->testLat, $this->testLong);
        $this->createStore($this->testStoreName,
            $this->testStoreLat, $this->testStoreLong, true, 1);

        $response = $this->json('POST', $this->storesAPI.$this->testPostcode);
        $response->assertOk()
            ->assertJsonFragment(['name' => $this->testStoreName]);
    }

    /**
     * Test API return if nearest store is closed.
     */
    public function testClosedStore(): void
    {
        $this->createPostcode($this->testPostcode, $this->testLat, $this->testLong);
        $this->createStore($this->testStoreName,
            $this->testStoreLat, $this->testStoreLong, false, 25);

        $response = $this->json('POST', $this->storesAPI.$this->testPostcode);
        $response->assertOk()->assertJson([]);
    }

    /**
     * Test delivery API.
     */
    public function testFoundDelivery(): void
    {
        $this->createPostcode($this->testPostcode, $this->testLat, $this->testLong);
        $this->createStore('Local Test 2 Store',
            50.47104628720212, -2.0487954581258435, true, 250);

        $response = $this->json('POST', "/api/delivery/".$this->testPostcode);
        $response->assertOk()
            ->assertJsonFragment(['name' => 'Local Test 2 Store']);
    }
}
