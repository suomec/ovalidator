<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\ValidatorException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be email
 */
class VEmail extends ValidatorBase
{
    public function check(mixed $value): mixed
    {
        if (!is_string($value)) {
            throw new ValidatorException($this->_('should be string'));
        }

        $value = mb_strtolower(trim($value));
        $value = filter_var($value, FILTER_VALIDATE_EMAIL);
        if ($value === false) {
            throw new ValidatorException($this->_('not an email'));
        }

        return $value;
    }

    public function getDescription(): string
    {
        return 'Email';
    }
}
