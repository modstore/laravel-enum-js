<?php

namespace App\Enums;

enum EnumWithSafeMethods: string
{
    case NAME = 'Name';
    case ACCOUNT_NAME = 'Email';

    /**
     * @enum-js-export
     */
    public static function columns(): array
    {
        return self::cases();
    }

    /**
     * @enum-js-export
     */
    public static function values(): array
    {
        $output = [];
        foreach (self::cases() as $case) {
            $output[] = [
                'title' => $case->name,
                'data' => $case->value,
            ];
        }

        return $output;
    }

    public static function thisIsNotSafe(): array
    {
        return ['time' => time()];
    }

    public static function thisIsAlsoNotSafe(): array
    {
        return ['random' => random_int(1, 10)];
    }
}
