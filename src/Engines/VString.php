<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be string. Returns string
 */
class VString extends ValidatorBase
{
    /** @var bool Remove edge spaces */
    private bool $doTrim;

    /**
     * @param bool $doTrim Apply trim function to string
     */
    public function __construct(bool $doTrim = false)
    {
        $this->doTrim = $doTrim;
    }

    public function check(mixed $value): mixed
    {
        if (!is_string($value)) {
            throw new EngineException($this->_('should be string'));
        }

        if ($this->doTrim) {
            $value = trim($value);
        }

        return $value;
    }

    public function getDescription(): string
    {
        $trim = $this->doTrim ? ' (auto-trim)' : '';

        return 'Should be string' . $trim;
    }
}
