<?php

declare(strict_types=1);

namespace OValidator\Exceptions;

class ValidationException extends \Exception
{
    /** @var array<string, string[]> */
    protected array $errors = [];

    /**
     * @param array<string, string[]> $errors
     * @return ValidationException
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @return array<string, string[]> Errors for fields
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
