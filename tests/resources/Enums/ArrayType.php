<?php

namespace App\Enums;

use App\Enums\Native\BackedInt;
use App\Enums\Native\BackedString;

final class ArrayType
{
    const IntArray = [
        BackedInt::Value1,
        BackedInt::Value2,
    ];
    const StringArray = [
        BackedString::Value1,
        BackedString::Value2,
    ];
}
