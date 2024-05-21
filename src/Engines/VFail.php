<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Always fails
 */
class VFail extends ValidatorBase
{
    private string $error;

    public function __construct(string $error)
    {
        $this->error = $error;
    }

    public function check(mixed $value): mixed
    {
        throw new EngineException($this->_('ERROR', ['error' => $this->error]));
    }

    public function getDescription(): string
    {
        return 'always fails';
    }
}
