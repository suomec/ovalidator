<?php

declare(strict_types=1);

namespace OValidator\Objects;

use OValidator\Interfaces\Validator;

/**
 * One field config container
 */
class FieldConfig
{
    private string $description;

    private State $state;

    /** @var Validator[] */
    private array $validators;

    /**
     * Field config object
     * @param string $description Description
     * @param State $state State
     * @param Validator[] $validators Engines
     */
    public function __construct(string $description, State $state, array $validators)
    {
        $this->description = $description;
        $this->state = $state;

        foreach ($validators as $validator) {
            if (!($validator instanceof Validator)) {
                throw new \Exception(get_class($validator) . ' is not instance of Validator');
            }
        }
        $this->validators = $validators;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }

    /**
     * @return Validator[]
     */
    public function getValidators(): array
    {
        return $this->validators;
    }
}
