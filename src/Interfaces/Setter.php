<?php

declare(strict_types=1);

namespace OValidator\Interfaces;

/**
 * Object fields setter/mapper
 */
interface Setter
{
    /**
     * Set object properties
     * @param object $object Object to map input
     * @param array<string, mixed> $validatedValues Fields [name => value] array of validated input values
     * @return ?ValidationResult Return ValidationResult on error or null on success
     */
    public function setProperties(object $object, array $validatedValues): ?ValidationResult;
}
