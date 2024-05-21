<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Objects\ValidatorBase;

/**
 * No checks
 */
class VSkip extends ValidatorBase
{
    public function check(mixed $value): mixed
    {
        return $value;
    }

    public function getDescription(): string
    {
        return 'dummy validator';
    }
}
