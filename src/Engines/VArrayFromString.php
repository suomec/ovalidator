<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Make array from string, separated by symbol
 */
class VArrayFromString extends ValidatorBase
{
    private string $separator;

    public function __construct(string $separator = ',')
    {
        if ($separator === '') {
            throw new \Exception("separator can't be empty");
        }

        $this->separator = $separator;
    }

    public function check(mixed $value): mixed
    {
        if (!is_string($value)) {
            throw new EngineException($this->_('value should be string'));
        }

        if ($value === '') {
            return [];
        }

        if (!str_contains($value, $this->separator)) {
            return [$value];
        }

        //@phpstan-ignore-next-line
        return explode($this->separator, $value);
    }

    public function getDescription(): string
    {
        return "array from string, separated by `{$this->separator}`";
    }
}
