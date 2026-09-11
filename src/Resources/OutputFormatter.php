<?php

namespace Modstore\LaravelEnumJs\Resources;

use ReflectionClass;

abstract class OutputFormatter
{
    protected const JS_EXPORT_TAG = '@enum-js-export';
    protected array $warnings = [];

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

        foreach ($this->getStaticMethodOutputs() as $name => $value) {
            $output .= $this->printStaticValue($name, $value);
        }

        $output .= $this->getEnd();

        return $output;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    protected function printStaticValue(string $name, mixed $value): string
    {
        return sprintf("export const %s = %s\n", $name, json_encode($value));
    }

    protected function getStaticMethodOutputs(): array
    {
        $methods = [];

        foreach ($this->class->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_STATIC) as $method) {
            // Only export methods from the same class.
            if ($method->getDeclaringClass()->getName() !== $this->class->getName()) {
                continue;
            }

            if (!$this->isExportableStaticMethod($method)) {
                continue;
            }

            try {
                $value = $method->invoke(null);
            } catch (\Throwable $exception) {
                $this->warnings[] = sprintf(
                    'Skipping @enum-js-export method %s::%s(): %s',
                    $this->class->getName(),
                    $method->getName(),
                    $exception->getMessage()
                );
                continue;
            }

            if ($value === null) {
                continue;
            }

            $methods[$method->getName()] = $this->normaliseJsonValue($value);
        }

        return $methods;
    }

    protected function isExportableStaticMethod(\ReflectionMethod $method): bool
    {
        return $method->getNumberOfParameters() === 0
            && $this->hasExportTag($method);
    }

    protected function hasExportTag(\ReflectionMethod $method): bool
    {
        return str_contains((string) $method->getDocComment(), self::JS_EXPORT_TAG);
    }

    protected function normaliseJsonValue(mixed $value): mixed
    {
        if ($value instanceof \BackedEnum) {
            return $value->value;
        }

        if ($value instanceof \UnitEnum) {
            return $value->name;
        }

        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->normaliseJsonValue($item);
            }

            return $value;
        }

        if (is_object($value)) {
            return $this->normaliseJsonValue(get_object_vars($value));
        }

        return $value;
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
