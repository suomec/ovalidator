<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be date and time in specific format. Returns DateTimeImmutable object or formatted string
 */
class VDateTime extends ValidatorBase
{
    private string $format;
    private bool $forceAsString;

    /**
     * @param string $format Date format
     * @param bool $forceAsString Should return result date as formatted string, not DateTimeImmutable
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
                throw new EngineException($this->_('CANT_PARSE', [
                    'format' => $this->format,
                ]));
            }

            $lastErrors = \DateTime::getLastErrors();
            if (is_array($lastErrors) && $lastErrors['warning_count'] > 0) {
                throw new EngineException($this->_('CANT_PARSE_IF_WARNINGS', [
                    'format' => $this->format,
                ]));
            }
            if (is_array($lastErrors) && $lastErrors['error_count'] > 0) {
                throw new EngineException($this->_('CANT_PARSE_IF_ERRORS', [
                    'format' => $this->format,
                ]));
            }
        }

        if ($instance === null) {
            throw new EngineException($this->_('BAD_TYPE'));
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
