<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be email
 */
class VEmail extends ValidatorBase
{
    public function check(mixed $value): mixed
    {
        if (!is_string($value)) {
            throw new EngineException($this->_('should be string'));
        }

        $value = filter_var(trim($value), FILTER_VALIDATE_EMAIL);
        if ($value === false) {
            throw new EngineException($this->_('not an email'));
        }

        return $value;
    }

    public function getDescription(): string
    {
        return 'Email';
    }
}
