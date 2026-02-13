<?php

declare(strict_types=1);

namespace Modstore\LaravelEnumJs\Tests\resources;


use Modstore\LaravelEnumJs\Resources\OutputFormatter;

final class CustomOutputFormatter extends OutputFormatter
{
    protected function printCase($case): string
    {
        $value = $this->getEnumValue($case);

        return sprintf("TEST NAME=%s, VALUE=%s\n", $case->getName(), json_encode($value));
    }
}
