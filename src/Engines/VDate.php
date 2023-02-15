<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\ValidatorException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be date in specific format
 */
class VDate extends ValidatorBase
{
    private string $format;
    private bool $asString;

    /**
     * @param string $format Date format
     * @param bool $asString Should return result date as string, not DateTimeImmutable
     */
    public function __construct(string $format, bool $asString = false)
    {
        $this->format = $format;
        $this->asString = $asString;
    }

    public function check(mixed $value): mixed
    {
        if (!is_string($value)) {
            throw new ValidatorException($this->_('should be string'));
        }

        $value = trim($value);

        $parsed = \DateTimeImmutable::createFromFormat($this->format, $value);
        if (is_bool($parsed)) {
            throw new ValidatorException($this->_("can't parse date for format: {format}", [
                'format' => $this->format,
            ]));
        }

        if ($this->asString) {
            return $parsed->format($this->format);
        }

        return $parsed;
    }

    public function getDescription(): string
    {
        return "Date ({$this->format})";
    }
}
