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
    private bool $excludeEmptyStrings;

    public function __construct(string $separator = ',', bool $excludeEmptyStrings = true)
    {
        if ($separator === '') {
            throw new \Exception("separator can't be empty");
        }

        $this->separator = $separator;
        $this->excludeEmptyStrings = $excludeEmptyStrings;
    }

    public function check(mixed $value): mixed
    {
        if (!is_string($value)) {
            throw new EngineException($this->_('NOT_STRING'));
        }

        if ($value === '') {
            return [];
        }

        if (!str_contains($value, $this->separator)) {
            return [$value];
        }

        //@phpstan-ignore-next-line
        $tmp = explode($this->separator, $value);

        $result = [];
        foreach ($tmp as $item) {
            if ($this->excludeEmptyStrings && $item === '') {
                continue;
            }

            $result[] = $item;
        }

        return $result;
    }

    public function getDescription(): string
    {
        return "array from items from string, separated by `{$this->separator}`";
    }
}
