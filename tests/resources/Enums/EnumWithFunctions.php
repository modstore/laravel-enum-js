<?php

namespace App\Enums;

enum EnumWithFunctions: string
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


    /**
     * @enum-js-export
     */
    public static function failingFunction(): array
    {
        throw new \Exception('This is a test exception');
        $output = [];
        foreach (self::cases() as $case) {
            $output[] = [
                'title' => $case->name,
                'data' => $case->value,
            ];
        }

        return $output;
    }

    public static function shouldNotGenerate(): array
    {
        return self::cases();
    }
}
