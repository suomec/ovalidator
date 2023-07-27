<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Float number
 */
class VFloat extends ValidatorBase
{
    public function check(mixed $value): mixed
    {
        if (is_string($value) && preg_match('|^[\d.]+$|', $value) && substr_count($value, '.') === 1) {
            $value = (float)$value;
        }

        if (!is_float($value)) {
            throw new EngineException($this->_('NOT_FLOAT'));
        }

        return $value;
    }

    public function getDescription(): string
    {
        return "float value";
    }
}
