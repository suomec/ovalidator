<?php

declare(strict_types=1);

namespace OValidator\Interfaces;

use OValidator\Objects\State;

/**
 * Request config
 */
interface Config
{
    /**
     * Add new field to list of checks
     * @param string $fieldName Name of field
     * @param string $description Description
     * @param State $state Required or not
     * @param Validator[] $validators List of validators
     * @return Config self
     */
    public function add(string $fieldName, string $description, State $state, array $validators): Config;

    /**
     * Adds validators from collection
     * @param string $fieldName Name of field
     * @param string $description Description
     * @param State $state Required or not
     * @param Collection $collection Collection
     * @return Config self
     */
    public function addCollection(string $fieldName, string $description, State $state, Collection $collection): Config;

    /**
     * Set extra fields names of request which should be ignored
     * @param string[] $fields Names of fields
     * @return void
     */
    public function setIgnoredExtraFields(array $fields): void;

    /**
     * Validate input data against rules created in add() method for each field
     * @param array<string, mixed> $values Request data
     * @return ValidationResult result with errors and values
     */
    public function validate(array $values): ValidationResult;

    /**
     * Sets localizer for translation
     * @param I18n $i18n Instance
     * @return void
     */
    public function setI18n(I18n $i18n): void;
}
