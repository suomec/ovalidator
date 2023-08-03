<?php

declare(strict_types=1);

namespace OValidator;

use OValidator\Exceptions\ValidationException;
use OValidator\Interfaces\Config as ConfigInterface;
use OValidator\Objects\LocPhpFile;
use OValidator\Setters\ReflectionSetter;

/**
 * Quick validator call with default parameters
 * If you need custom settings/mappers - create own method like validateAndSet
 */
class OValidator
{
    /**
     * Throws ValidationException at any error
     * @param ConfigInterface $config Fields check config
     * @param array<string, mixed> $input User data
     * @param object $object Object to map
     * @param string $localizationName
     */
    public static function validateAndSet(
        ConfigInterface $config,
        array $input,
        object $object,
        string $localizationName = 'loc-en.php',
    ): void {
        $localization = new LocPhpFile(__DIR__ . "/../etc/{$localizationName}");

        $form = Form::make($input);
        $result = (new Mapper($form, $config, $localization))->toObject($object, new ReflectionSetter());

        if ($result !== null && $result->hasErrors()) {
            throw (new ValidationException())->setErrors($result->getErrors());
        }
    }
}
