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

        if (in_array($value, [1, '1', true, 'true', 'on', 'yes'], true)) {
            return true;
        }

        if (in_array($value, [0, '0', false, 'false', 'off', 'no'], true)) {
            return false;
        }

        throw new EngineException($this->_('BAD_FORMAT'));
    }

    public function getDescription(): string
    {
        return 'Must be 1 or 0 / true or false / "true" or "false" / "on" or "off" / "yes" or "no"';
    }
}
