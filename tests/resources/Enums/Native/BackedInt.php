<?php

namespace App\Enums\Native;

enum BackedInt : int
{
    case Value1 = 1;
    case Value2 = 2;

    const ADDITIONAL_CONST = [
        'example',
    ];
}
