<?php

declare(strict_types=1);

namespace OValidator;

use OValidator\Exceptions\ValidationException;
use OValidator\Interfaces\Config as ConfigInterface;
use OValidator\Setters\ReflectionSetter;

class OValidator
{
    /**
     * Quick validator call with default parameters
     * Throws ValidationException at any error
     * @param array<string, mixed> $input User data
     * @param object $object Object to map
     */
    public static function validateAndSet(ConfigInterface $config, array $input, object $object): void
    {
        $form = (new Form())->fromArray($input);
        $result = (new Mapper($form, $config))->toObject($object, new ReflectionSetter());

        if ($result !== null && $result->hasErrors()) {
            throw (new ValidationException())->setErrors($result->getErrors());
        }
    }
}
