<?php

namespace App\Enums\Native;

enum BackedString : string
{
    case Value1 = 'value-1';
    case Value2 = 'value-2';

    const ADDITIONAL_CONST = [
        'example',
    ];
}
