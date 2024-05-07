<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UKPostcode implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('#^(GIR ?0AA|'.
                    '[A-PR-UWYZ]([0-9]{1,2}|'.
                    '([A-HK-Y][0-9]([0-9ABEHMNPRV-Y])?)|'.
                    '[0-9][A-HJKPS-UW]) ?[0-9][ABD-HJLNP-UW-Z]{2})$#', $value) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid UK postcode.';
    }
}

