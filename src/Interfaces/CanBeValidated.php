<?php

declare(strict_types=1);

namespace OValidator\Interfaces;

/**
 * Applies to object that can be validated using Config
 */
interface CanBeValidated
{
    public function getValidationConfig(): Config;
}
