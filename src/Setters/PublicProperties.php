<?php

declare(strict_types=1);

namespace OValidator\Setters;

use OValidator\Interfaces\Setter;
use OValidator\Interfaces\ValidationResult;
use OValidator\Objects\Result;

/**
 * Setter only for public properties of object
 */
class PublicProperties implements Setter
{
    /** @var array<string, array<mixed>> */
    public static array $reflectionCache = [];

    /**
     * Returns object public properties
     * @param object $object Object
     * @return string[] Properties names
     */
    private function getPublicProperties(object $object): array
    {
        if (isset(self::$reflectionCache[get_class($object)])) {
            //@phpstan-ignore-next-line
            return self::$reflectionCache[get_class($object)]['properties'];
        }

        $r = new \ReflectionClass($object);
        $rProperties = $r->getProperties(\ReflectionProperty::IS_PUBLIC);

        $properties = [];
        $types = [];
        foreach ($rProperties as $property) {
            $properties[] = $property->getName();
            $types[$property->getName()] = $property->getType();
        }

        self::$reflectionCache[get_class($object)] = [
            'properties' => $properties,
            'types' => $types,
        ];

        return $properties;
    }

    public function setProperties(object $object, array $values): ?ValidationResult
    {
        $properties = $this->getPublicProperties($object);
        $types = self::$reflectionCache[get_class($object)]['types'];
        $result = new Result();

        foreach ($properties as $property) {
            if (!array_key_exists($property, $values)) {
                $result->addError($property, 'field not found in validated request');
                continue;
            }

            $value = $values[$property];

            /** @var \ReflectionNamedType $objectPropertyType|null */
            //@phpstan-ignore-next-line
            $objectPropertyType = $types[$property];

            if ($objectPropertyType !== null) {
                // for typed property check if type is correct
                $valueType = gettype($value);
                $isObject = false;
                if ($valueType === 'object') {
                    //@phpstan-ignore-next-line
                    $valueType = get_class($value);
                    $isObject = true;
                }
                if ($valueType === 'integer') {
                    $valueType = 'int';
                }
                if ($valueType === 'boolean') {
                    $valueType = 'bool';
                }

                if ($objectPropertyType->allowsNull() && $value === null) {
                    $object->$property = null;
                    continue;
                } elseif (!$objectPropertyType->allowsNull() && $value === null) {
                    $result->addError($property, "doesn't not allow NULL values");
                    continue;
                }

                if ($isObject && is_string($valueType)) {
                    $interfaces = class_implements($valueType);
                    if (is_array($interfaces) && in_array($objectPropertyType->getName(), $interfaces, true)) {
                        $object->$property = $value;
                        continue;
                    }
                }

                if ($objectPropertyType->getName() !== $valueType) {
                    $result->addError($property, 'assign types mismatch');
                    continue;
                }
            }

            $object->$property = $value;
        }

        if ($result->hasErrors()) {
            return $result;
        }

        return null;
    }
}
