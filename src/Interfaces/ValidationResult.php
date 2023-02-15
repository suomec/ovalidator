<?php

declare(strict_types=1);

namespace OValidator\Interfaces;

/**
 * Object fields manipulator
 */
interface ValidationResult
{
    /**
     * @return array<string, string[]> Validation errors
     */
    public function getErrors(): array;

    /**
     * @return bool Has any error field
     */
    public function hasErrors(): bool;

    /**
     * Add new error
     * @param string $field Field name
     * @param string $error Error
     * @return void
     */
    public function addError(string $field, string $error): void;

    /**
     * @return array<string, mixed> Field values
     */
    public function getValues(): array;

    /**
     * Set new fields values
     * @param array<string, mixed> $values
     * @return void
     */
    public function setValues(array $values): void;
}
