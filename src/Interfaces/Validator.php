<?php

declare(strict_types=1);

namespace OValidator\Interfaces;

/**
 * What validator should do
 */
interface Validator
{
    /**
     * Main check
     * @param mixed $value Input data
     * @return mixed Result
     */
    public function check(mixed $value): mixed;

    /**
     * @return string Validator description, based on its config and type
     */
    public function getDescription(): string;

    /**
     * Set localization object
     * @param I18n $i18n Instance
     * @return void
     */
    public function setI18n(I18n $i18n): void;
}
