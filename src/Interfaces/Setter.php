<?php

declare(strict_types=1);

namespace OValidator\Interfaces;

/**
 * Object fields setter
 */
interface Setter
{
    /**
     * Set object property
     * @param object $object Object
     * @param array<string, mixed> $values Fields [name => value] array
     * @return ?ValidationResult Can return ValidationResult on error or null on success
     */
    public function setProperties(object $object, array $values): ?ValidationResult;
}
