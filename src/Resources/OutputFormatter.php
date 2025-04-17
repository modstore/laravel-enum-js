<?php

namespace Modstore\LaravelEnumJs\Resources;

use ReflectionClass;

abstract class OutputFormatter
{
    public function __construct(protected readonly ReflectionClass $class)
    {
    }

    /**
     * Define how each of the enum cases should be printed.
     *
     * @param $case
     * @return string
     */
    protected abstract function printCase($case): string;

    /**
     * Define how the start of the file should be printed.
     *
     * @return string
     */
    protected function getStart(): string
    {
        return '';
    }

    /**
     * Define how the end of the file should be printed.
     *
     * @return string
     */
    protected function getEnd(): string
    {
        return '';
    }

    public function getFileContents(): string
    {
        $output = $this->getStart();

        foreach ($this->class->getReflectionConstants() as $case) {
            $output .= $this->printCase($case);
        }

        $output .= $this->getEnd();

        return $output;
    }

    protected function getEnumValue($enumCase): mixed
    {
        $value = $enumCase->getValue();
        if (method_exists($enumCase, 'isEnumCase') && $enumCase->isEnumCase()) {
            $value = property_exists($value, 'value') ? $value->value : $value->name;
        }

        return $value;
    }
}