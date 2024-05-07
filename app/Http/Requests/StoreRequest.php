<?php

namespace App\Http\Requests;

use App\Enums\StoreEnum;
use App\Helpers\EnumHelper;
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
            'lat' => 'required|numeric|between:-90,90',
            'long' => 'required|numeric|between:-180,180',
            'is_open' => 'required|boolean',
            'store_type' => [
                'required',
                Rule::in(EnumHelper::getEnumValues(StoreEnum::cases() ?? [])),
            ],
            'max_delivery_distance' => 'required|numeric',
        ];
    }

}
