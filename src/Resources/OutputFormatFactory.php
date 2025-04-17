<?php

namespace Modstore\LaravelEnumJs\Resources;

use ReflectionClass;

class OutputFormatFactory
{

    /**
     * @param string $type
     * @param ReflectionClass $class
     * @return OutputFormatter
     */
    public static function create(string $type, ReflectionClass $class): OutputFormatter
    {
        return match ($type) {
            'object' => new ObjectFormatter($class),
            'constant' => new ConstantFormatter($class),
            default => throw new \InvalidArgumentException("Invalid output format type: {$type}"),
        };
    }

}