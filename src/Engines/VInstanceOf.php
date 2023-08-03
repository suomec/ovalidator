<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be an instance of type
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
            throw new EngineException($this->_('NOT_INSTANCE', [
                'type' => $this->needType,
            ]));
        }

        return $value;
    }

    public function getDescription(): string
    {
        return 'instance of: ' . $this->needType;
    }
}
