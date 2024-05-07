<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GeoCoordinates implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (!is_string($value) || !strpos($value, ',')) {
            return false;
        }

        [$latitude, $longitude] = explode(',', $value, 2);

        $latitude = trim($latitude);
        $longitude = trim($longitude);

        return $this->isValidLatitude($latitude) && $this->isValidLongitude($longitude);
    }

    /**
     * Validate if the given value is a valid latitude.
     *
     * @param string $latitude
     * @return bool
     */
    protected function isValidLatitude(string $latitude): bool
    {
        return is_numeric($latitude) && $latitude >= -90 && $latitude <= 90;
    }

    /**
     * Validate if the given value is a valid longitude.
     *
     * @param string $longitude
     *
     * @return bool
     */
    protected function isValidLongitude(string $longitude): bool
    {
        return is_numeric($longitude) && $longitude >= -180 && $longitude <= 180;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be a valid latitude and longitude separated by a comma.';
    }
}
