<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\ValidatorException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be in set
 */
class VInSet extends ValidatorBase
{
    /** @var array<int, string|int|float> */
    private array $allowedValues;

    /**
     * @param array<int, string|int|float> $allowedValues Allowed values for user input
     */
    public function __construct(array $allowedValues)
    {
        if (count($allowedValues) === 0) {
            throw new \Exception($this->_("allowedValues can't be empty"));
        }

        $this->allowedValues = $allowedValues;
    }

    public function check(mixed $value): mixed
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            throw new ValidatorException($this->_('value type should be: string, int, float'));
        }

        //если входной массив - это массив целых чисел, а не строк, то нам надо проверять именно как целое
        //и вернуть целое число как результат
        $firstKey = array_key_first($this->allowedValues);
        $firstValue = $this->allowedValues[$firstKey];
        if (is_int($firstValue)) {
            if ((string)(int)$value !== (string)$value) {
                throw new ValidatorException($this->_('value is not allowed (not in set)'));
            }
            $value = (int)$value;
        }

        if (!in_array($value, $this->allowedValues, true)) {
            throw new ValidatorException($this->_('value is not allowed (not in set)'));
        }

        return $value;
    }

    public function getDescription(): string
    {
        $slice = $this->allowedValues;
        if (count($this->allowedValues) > 15) {
            $slice = array_slice($this->allowedValues, 0, 15);
            $slice[] = '... ';
        }

        $allowed = '[' . implode(', ', $slice) . ']';

        return 'Value should be in set: ' . $allowed;
    }
}
