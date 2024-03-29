<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Integer number
 */
class VInteger extends ValidatorBase
{
    public function check(mixed $value): mixed
    {
        if (!is_numeric($value)) {
            throw new EngineException($this->_('NOT_NUMERIC'));
        }

        if (is_float($value)) {
            throw new EngineException($this->_('CANT_BE_FLOAT'));
        }

        // "+" before number, ('+17' -> '17')
        if (is_string($value) && isset($value[0]) && $value[0] === '+') {
            $value = substr($value, 1);
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        // float without .XXX part
        if ((string)(int)$value !== (string)$value) {
            throw new EngineException($this->_('LOOKS_LIKE_FLOAT'));
        }

        return (int)$value;
    }

    public function getDescription(): string
    {
        return "integer value";
    }
}
