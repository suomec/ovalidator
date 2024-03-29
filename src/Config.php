<?php

declare(strict_types=1);

namespace OValidator;

use OValidator\Exceptions\EngineException;
use OValidator\Interfaces\Collection;
use OValidator\Interfaces\Config as ConfigInterface;
use OValidator\Interfaces\Localization;
use OValidator\Interfaces\ValidationResult;
use OValidator\Objects\FieldConfig;
use OValidator\Objects\Result;
use OValidator\Objects\State;

class Config implements ConfigInterface
{
    private bool $disableExtraFields;
    /** @var string[] */
    private array $ignoredExtraFields;
    /** @var array<string, FieldConfig> */
    private array $fields;

    public function __construct(bool $disableExtraFields = true)
    {
        $this->fields = [];
        $this->ignoredExtraFields = [];
        $this->disableExtraFields = $disableExtraFields;
    }

    public function add(string $fieldName, string $description, State $state, array $validators): ConfigInterface
    {
        $this->fields[$fieldName] = new FieldConfig($description, $state, $validators);

        return $this;
    }

    public function addCollection(string $fieldName, string $description, State $state, Collection $collection): ConfigInterface
    {
        $this->add($fieldName, $description, $state, $collection->getValidators());

        return $this;
    }

    public function setIgnoredExtraFields(array $fields): void
    {
        $this->ignoredExtraFields = $fields;
    }

    public function validate(array $values, Localization $localization): ValidationResult
    {
        $result = new Result();

        foreach ($values as $k => $_) {
            if (!is_string($k)) {
                $badField = new Result();
                $badField->addError('unknown', 'all input fields keys should be strings');

                return $badField;
            }
        }

        if ($this->disableExtraFields) {
            foreach ($values as $k => $_) {
                $fieldConfigSet = array_key_exists($k, $this->fields);
                $fieldIgnored = in_array($k, $this->ignoredExtraFields);

                if (!$fieldConfigSet && !$fieldIgnored) {
                    $result->addError($k, 'extra field not allowed');
                }
            }
        }

        $validated = [];
        foreach ($this->fields as $name => $config) {
            $validated[$name] = $this->validateAndGet($name, $config, $values, $result, $localization);
        }

        $result->setValues($validated);

        return $result;
    }

    /**
     * @param string $name Field name
     * @param FieldConfig $config Field config
     * @param array<string, mixed> $values Input values
     * @param ValidationResult $result Validation result container
     * @param Localization $localization Localization config
     * @return mixed New value
     */
    private function validateAndGet(
        string $name,
        FieldConfig $config,
        array $values,
        ValidationResult $result,
        Localization $localization,
    ): mixed {
        if (!array_key_exists($name, $values) || $values[$name] === null) {
            switch ($config->getState()) {
                case State::Required:
                    $result->addError($name, 'field is required but not passed');
                    return null;

                case State::Optional:
                    return null;
            }
        }

        $newValue = $values[$name];
        foreach ($config->getValidators() as $validator) {
            $validator->setLocalization($localization);

            try {
                $newValue = $validator->check($newValue);
            } catch (EngineException $e) {
                $result->addError($name, $e->getMessage());
                return null;
            }
        }

        return $newValue;
    }
}
