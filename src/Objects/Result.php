<?php

declare(strict_types=1);

namespace OValidator\Objects;

use OValidator\Interfaces\ValidationResult;

/**
 * Errors and values container
 */
class Result implements ValidationResult
{
    /** @var array<string, string[]> */
    private array $errors = [];
    /** @var array<string, mixed> */
    private array $values = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function addError(string $field, string $error): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }

        $this->errors[$field][] = $error;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function setValues(array $values): void
    {
        $this->values = $values;
    }
}
