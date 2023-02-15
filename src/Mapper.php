<?php

declare(strict_types=1);

namespace OValidator;

use OValidator\Interfaces\Config as ConfigInterface;
use OValidator\Interfaces\Form as FormInterface;
use OValidator\Interfaces\I18n;
use OValidator\Interfaces\Setter;
use OValidator\Interfaces\ValidationResult;

/**
 * Map and validate input data
 */
class Mapper
{
    private FormInterface $form;
    private ConfigInterface $config;
    private ?I18n $i18n;

    public function __construct(FormInterface $form, ConfigInterface $config, ?I18n $i18n = null)
    {
        $this->form = $form;
        $this->config = $config;
        $this->i18n = $i18n;
    }

    /**
     * Apply validated fields to public object properties
     * @param object $object Some object with public fields
     * @return ?ValidationResult Validation result object on error or null if successful
     */
    public function toObject(object $object, Setter $setter): ?ValidationResult
    {
        if ($this->i18n !== null) {
            $this->config->setI18n($this->i18n);
        }

        $result = $this->config->validate($this->form->export());

        $errors = $result->getErrors();
        if (count($errors) > 0) {
            return $result;
        }

        return $setter->setProperties($object, $result->getValues());
    }
}
