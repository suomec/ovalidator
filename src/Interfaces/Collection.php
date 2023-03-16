<?php

declare(strict_types=1);

namespace OValidator\Interfaces;

/**
 * Collection of validators combined
 */
interface Collection
{
    /**
     * @return Validator[] List of validators
     */
    public function getValidators(): array;
}
