<?php

namespace Modstore\LaravelEnumJs\Resources;

use ReflectionClass;

class ObjectFormatter extends OutputFormatter
{
    public function __construct(ReflectionClass $class)
    {
        parent::__construct($class);
    }

    protected function printCase($case): string
    {
        $value = $this->getEnumValue($case);

        return sprintf("  %s: %s,\n", $case->getName(), json_encode($value));
    }

    protected function getStart(): string
    {
        $exploded=explode('\\', $this->class->getName());
        $objectName = end($exploded);

        return sprintf("export const %s = Object.freeze({\n", $objectName);
    }

    protected function getEnd(): string
    {
        return '})';
    }
}