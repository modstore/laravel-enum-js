<?php

namespace App\Enums\Native;

enum Base
{
    case Value1;
    case Value2;

    const ADDITIONAL_CONST = [
        'example',
    ];
}
