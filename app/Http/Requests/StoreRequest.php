<?php

namespace App\Http\Requests;

use App\Enums\StoreEnum;
use App\Helpers\EnumHelper;
use App\Rules\GeoCoordinates;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'geo_coordinates' => [
                'required',
                'string',
                new GeoCoordinates(),
            ],
            'is_open' => 'required|boolean',
            'store_type' => [
                'required',
                Rule::in(EnumHelper::getEnumValues(StoreEnum::cases() ?? [])),
            ],
            'max_delivery_distance' => 'required|numeric',
        ];
    }

}
