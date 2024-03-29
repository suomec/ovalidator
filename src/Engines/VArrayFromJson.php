<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Validator which makes array from JSON-string
 */
class VArrayFromJson extends ValidatorBase
{
    private int $depth;
    private int $flags;

    /**
     * @param int $depth First argument for json_decode
     * @param int $flags Second argument for json_decode
     */
    public function __construct(int $depth = 512, int $flags = 0)
    {
        $this->depth = $depth;
        $this->flags = $flags;
    }

    public function check(mixed $value): mixed
    {
        if ($this->depth <= 0) {
            throw new EngineException($this->_('DEPTH_TOO_SMALL'));
        }

        if (!is_string($value)) {
            throw new EngineException($this->_('NOT_STRING'));
        }

        $decoded = json_decode($value, true, $this->depth, $this->flags);
        if (!is_array($decoded)) {
            throw new EngineException($this->_('NOT_ARRAY'));
        }

        return $decoded;
    }

    public function getDescription(): string
    {
        return 'array from JSON-encoded string';
    }
}
