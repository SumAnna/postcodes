<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostcodeRequest;
use App\Http\Requests\StoreRequest;
use App\Models\Postcode;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class StoreController extends Controller
{
    /**
     * Add a new store to the database.
     *
     * @param  StoreRequest  $request
     *
     * @return JsonResponse
     */
    public function add(StoreRequest $request): JsonResponse
    {
        try {
            $userId = Auth::id();

            if (null === $userId) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $storeData = $request->validated();
            $storeData['user_id'] = $userId;

            $store = Store::create($storeData);
        } catch (Exception $e) {
            Log::error('Error adding store: ' . $e->getMessage());

            return response()->json(['message' => 'Error adding store'], 500);
        }

        return response()->json(['message' => 'Store added successfully!', 'store' => $store], 201);
    }

    /**
     * Get stores near postcode.
     *
     * @param  StorePostcodeRequest $request
     *
     * @return JsonResponse
     */
    public function getStoresNearPostcode(StorePostcodeRequest $request): JsonResponse
    {
        return $this->getStoresByPostcode($request);
    }

    /**
     * Get stores that will be able to deliver to the chosen postcode.
     *
     * @param  StorePostcodeRequest $request
     *
     * @return JsonResponse
     */
    public function getDeliverableStores(StorePostcodeRequest $request): JsonResponse
    {
        return $this->getStoresByPostcode($request, true);
    }

    /**
     * Get stores by postcode.
     *
     * @param  StorePostcodeRequest $request
     * @param  bool $canDeliver
     *
     * @return JsonResponse
     */
    private function getStoresByPostcode(
        StorePostcodeRequest $request,
        bool $canDeliver = false
    ): JsonResponse {
        $validated = $request->validated();

        $postcodeData = Postcode::where('pcd', $validated['postcode'])->first();

        if (!$postcodeData) {
            return response()->json(['message' => 'Postcode not found.'], 404);
        }

        $stores = Store::nearCoordinates($postcodeData->lat, $postcodeData->long, $canDeliver, $canDeliver ? 0 : $validated['radius'] ?? 1);

        return response()->json($stores);
    }

}

