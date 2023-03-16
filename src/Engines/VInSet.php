<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be in set of values. Returns input value if it's correct or value index in allowed-list array
 * Supports string, int and float values
 */
class VInSet extends ValidatorBase
{
    /** @var array<int, string|int|float> */
    private array $allowedValues;
    private bool $returnIndex;

    /**
     * @param array<int, string|int|float> $allowedValues Allowed values for user input
     * @param bool $returnIndex Should return index of item, not item
     */
    public function __construct(array $allowedValues, bool $returnIndex = false)
    {
        if (count($allowedValues) === 0) {
            throw new \Exception($this->_("allowedValues list can't be empty"));
        }

        $this->allowedValues = $allowedValues;
        $this->returnIndex = $returnIndex;
    }

    public function check(mixed $value): mixed
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            throw new EngineException($this->_('value type should be: string, int, float'));
        }

        if (!in_array($value, $this->allowedValues, true)) {
            throw new EngineException($this->_('value is not allowed (not in set)'));
        }

        if (!$this->returnIndex) {
            return $value;
        }

        foreach ($this->allowedValues as $k => $v) {
            if ($v === $value) {
                return $k;
            }
        }

        throw new EngineException($this->_('value by index not found'));
    }

    public function getDescription(): string
    {
        $slice = $this->allowedValues;
        if (count($this->allowedValues) > 15) {
            $slice = array_slice($this->allowedValues, 0, 15);
            $slice[] = '... ';
        }

        $allowed = '[' . implode(', ', $slice) . ']';

        return 'Value should belong to set: ' . $allowed;
    }
}
