<?php

namespace App\Http\Requests;

use App\Rules\UKPostcode;
use Illuminate\Foundation\Http\FormRequest;

class StorePostcodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'postcode' => [
                'required',
                'string',
                new UKPostcode(),
            ],
            'radius' => 'nullable|numeric|min:0'
        ];
    }

    /**
     * Override this method to include the route parameters in the validation data.
     *
     * @return array
     */
    public function validationData(): array
    {
        return array_merge($this->all(), [
            'postcode' => $this->route('postcode'),
            'radius' => $this->route('radius'),
        ]);
    }

}
