<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\ValidatorException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be instance of type
 */
class VInstanceOf extends ValidatorBase
{
    private string $needType;

    public function __construct(string $needType)
    {
        $this->needType = $needType;
    }

    public function check(mixed $value): mixed
    {
        if (!($value instanceof $this->needType)) {
            throw new ValidatorException($this->_('should be instance of: {type}', [
                'type' => $this->needType,
            ]));
        }

        return $value;
    }

    public function getDescription(): string
    {
        return 'Instance of: ' . $this->needType;
    }
}
