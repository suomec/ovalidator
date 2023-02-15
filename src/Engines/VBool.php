<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\ValidatorException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be boolean
 */
class VBool extends ValidatorBase
{
    public function check(mixed $value): mixed
    {
        if (in_array($value, [0, '0', false, 'false'], true)) {
            $value = false;
        } elseif (in_array($value, [1, '1', true, 'true'], true)) {
            $value = true;
        } else {
            throw new ValidatorException($this->_('should have boolean format'));
        }

        return $value;
    }

    public function getDescription(): string
    {
        return 'Must be 1 or 0 / true or false / "true" or "false" (as strings)';
    }
}
