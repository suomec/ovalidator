<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be date and time in specific format. Returns DateTimeImmutable object
 */
class VDateTime extends ValidatorBase
{
    private string $format;
    private bool $forceAsString;

    /**
     * @param string $format Date format
     * @param bool $forceAsString Should return result date as string, not DateTimeImmutable
     */
    public function __construct(string $format, bool $forceAsString = false)
    {
        $this->format = $format;
        $this->forceAsString = $forceAsString;
    }

    public function check(mixed $value): mixed
    {
        $instance = null;

        if ($value instanceof \DateTimeImmutable) {
            $instance = $value;
        }

        if (is_string($value)) {
            $instance = \DateTimeImmutable::createFromFormat($this->format, trim($value));
            if (is_bool($instance)) {
                throw new EngineException($this->_("can't parse date for format: {format}", [
                    'format' => $this->format,
                ]));
            }
        }

        if ($instance === null) {
            throw new EngineException($this->_('should be string or DateTimeImmutable'));
        }

        if ($this->forceAsString) {
            return $instance->format($this->format);
        }

        return $instance;
    }

    public function getDescription(): string
    {
        return "DateTime ({$this->format})";
    }
}
