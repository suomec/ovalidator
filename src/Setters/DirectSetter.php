<?php

declare(strict_types=1);

namespace OValidator\Setters;

use OValidator\Interfaces\Setter;
use OValidator\Interfaces\ValidationResult;
use OValidator\Objects\Result;

/**
 * Fast setter via variable property names ($object->$field = $value)
 */
class DirectSetter implements Setter
{
    public function setProperties(object $object, array $validatedValues): ?ValidationResult
    {
        $result = new Result();

        foreach ($validatedValues as $field => $value) {
            if (!property_exists($object, $field)) {
                $result->addError($field, "property doesn't exists");

                continue;
            }

            try {
                $object->$field = $value;
            } catch (\TypeError $e) {
                $result->addError($field, 'assign type mismatch');

                continue;
            }
        }

        if ($result->hasErrors()) {
            return $result;
        }

        return null;
    }
}
