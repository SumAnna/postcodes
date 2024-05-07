<?php

namespace App\Helpers;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionEnum;
use ReflectionException;

class EnumHelper
{
    /**
     * Convert enum cases to string.
     *
     * @param  array $enumValues The enum class name.
     *
     * @return array
     */
    public static function getEnumValues(array $enumValues): array
    {
        return array_column($enumValues, 'value');
    }
}
