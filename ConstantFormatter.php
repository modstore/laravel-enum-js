<?php

namespace Modstore\LaravelEnumJs\Resources;

use ReflectionClass;

class ConstantFormatter extends OutputFormatter
{
    public function __construct(ReflectionClass $class)
    {
        parent::__construct($class);
    }

    protected function printCase($case): string
    {
        $value = $this->getEnumValue($case);

        return sprintf("export const %s = %s\n", $case->getName(), json_encode($value));
    }
}