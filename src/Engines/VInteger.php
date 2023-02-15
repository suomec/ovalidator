<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\ValidatorException;
use OValidator\Objects\ValidatorBase;

/**
 * Integer number
 */
class VInteger extends ValidatorBase
{
    public function check(mixed $value): mixed
    {
        if (!is_numeric($value)) {
            throw new ValidatorException($this->_('not numeric'));
        }

        if (is_float($value)) {
            throw new ValidatorException($this->_('is float'));
        }

        // trailing "+", ('+17' -> '17')
        if (is_string($value) && isset($value[0]) && $value[0] === '+') {
            $value = substr($value, 1);
        }

        // float without .XXX part
        if ((string)(int)$value !== (string)$value) {
            throw new ValidatorException($this->_('is float'));
        }

        return (int)$value;
    }

    public function getDescription(): string
    {
        return "Integer value";
    }
}
