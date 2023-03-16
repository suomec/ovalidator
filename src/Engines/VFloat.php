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
        if (!is_float($value)) {
            throw new EngineException($this->_('is not float'));
        }

        return $value;
    }

    public function getDescription(): string
    {
        return "float value";
    }
}
