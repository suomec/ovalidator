<?php

declare(strict_types=1);

namespace OValidator\Setters;

use OValidator\Interfaces\Setter;
use OValidator\Interfaces\ValidationResult;
use OValidator\Objects\Result;

/**
 * Setter for typed and not typed public properties of object based on reflection information
 */
class ReflectionSetter implements Setter
{
    /** @var array<string, array<string, ?\ReflectionNamedType>> */
    public static array $reflectionCache = [];

    public function setProperties(object $object, array $validatedValues): ?ValidationResult
    {
        $result = new Result();

        $fields = $this->getPublicProperties($object);
        foreach ($fields as $field => $type) {
            $v = $this->getValueToSet($field, $validatedValues, $type);
            if ($v['error'] !== null) {
                $result->addError($field, $v['error']);

                continue;
            }

            $object->$field = $v['value'];
        }

        if ($result->hasErrors()) {
            return $result;
        }

        return null;
    }

    /**
     * @param string $field
     * @param array<string, mixed> $validatedValues
     * @param \ReflectionNamedType|null $dstType
     * @return array{error: ?string, value: mixed}
     */
    private function getValueToSet(string $field, array $validatedValues, ?\ReflectionNamedType $dstType): array
    {
        if (!array_key_exists($field, $validatedValues)) {
            if ($dstType !== null && $dstType->allowsNull()) {
                return ['error' => null, 'value' => null];
            }

            return ['error' => 'not found in validated request and not nullable', 'value' => null];
        }

        $validatedInput = $validatedValues[$field];

        $srcType = gettype($validatedInput);
        $srcType = str_replace(['integer', 'boolean', 'double'], ['int', 'bool', 'float'], $srcType);

        if ($dstType === null) {
            // not typed object property
            return ['error' => null, 'value' => $validatedInput];
        }

        $dstTypeName = $dstType->getName();

        if (is_object($validatedInput)) {
            // same class
            if ($dstTypeName === get_class($validatedInput)) {
                return ['error' => null, 'value' => $validatedInput];
            }

            $interfaces = class_implements(get_class($validatedInput));
            if (is_array($interfaces) && in_array($dstTypeName, $interfaces, true)) {
                return ['error' => null, 'value' => $validatedInput];
            }

            return ['error' => "object value can't be applied", 'value' => null];
        }

        if ($dstType->allowsNull() && $validatedInput === null) {
            return ['error' => null, 'value' => null];
        }

        if (!$dstType->allowsNull() && $validatedInput === null) {
            return ['error' => "doesn't not allow NULL values", 'value' => null];
        }

        if ($dstTypeName !== $srcType) {
            return [
                'error' => sprintf('assign types mismatch (%s != %s)', $dstTypeName, $srcType),
                'value' => null,
            ];
        }

        return ['error' => null, 'value' => $validatedInput];
    }

    /**
     * Returns object public properties
     * @param object $object Object
     * @return array<string, ?\ReflectionNamedType> Properties names and types
     */
    private function getPublicProperties(object $object): array
    {
        $class = get_class($object);

        if (isset(self::$reflectionCache[$class])) {
            return self::$reflectionCache[$class];
        }

        $rProperties = (new \ReflectionClass($object))->getProperties(\ReflectionProperty::IS_PUBLIC);
        $properties = [];

        foreach ($rProperties as $property) {
            $type = $property->getType();
            if (!($type instanceof \ReflectionNamedType)) {
                $type = null;
            }

            $properties[$property->getName()] = $type;
        }

        self::$reflectionCache[$class] = $properties;

        return $properties;
    }
}
