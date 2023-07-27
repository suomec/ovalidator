<?php

declare(strict_types=1);

namespace OValidator;

use OValidator\Interfaces\Config as ConfigInterface;
use OValidator\Interfaces\Form as FormInterface;
use OValidator\Interfaces\Localization;
use OValidator\Interfaces\Setter;
use OValidator\Interfaces\ValidationResult;

/**
 * Map and validate input data
 */
class Mapper
{
    private FormInterface $form;
    private ConfigInterface $config;
    private Localization $localization;

    public function __construct(FormInterface $form, ConfigInterface $config, Localization $localization)
    {
        $this->form = $form;
        $this->config = $config;
        $this->localization = $localization;
    }

    /**
     * Apply validated fields to public object properties
     * @param object $object Some object with public fields
     * @param Setter $setter Setter object
     * @return ?ValidationResult Validation result object on error or null if successful
     */
    public function toObject(object $object, Setter $setter): ?ValidationResult
    {
        $result = $this->config->validate($this->form->export(), $this->localization);

        $errors = $result->getErrors();
        if (count($errors) > 0) {
            return $result;
        }

        return $setter->setProperties($object, $result->getValues());
    }
}
