<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be boolean. Returns bool value
 */
class VBool extends ValidatorBase
{
    public function check(mixed $value): mixed
    {
        if (is_string($value)) {
            $value = strtolower($value);
        }

        if (in_array($value, [0, '0', false, 'false', 'off', 'no'], true)) {
            $value = false;
        } elseif (in_array($value, [1, '1', true, 'true', 'on', 'yes'], true)) {
            $value = true;
        } else {
            throw new EngineException($this->_('should have boolean format'));
        }

        return $value;
    }

    public function getDescription(): string
    {
        return 'Must be 1 or 0 / true or false / "true" or "false" (as strings)';
    }
}
