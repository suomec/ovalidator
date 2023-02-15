<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\ValidatorException;
use OValidator\Objects\ValidatorBase;

/**
 * Max size/count of items
 */
class VMax extends ValidatorBase
{
    /** @var int Max value */
    private int $maxSize;

    public function __construct(int $maxSize)
    {
        $this->maxSize = $maxSize;
    }

    public function check(mixed $value): mixed
    {
        switch (true) {
            case is_float($value):
            case is_int($value):
                $size = $value;
                $errorMessage = $this->_('number must be less than {max}', ['max' => $this->maxSize]);
                break;

            case is_string($value):
                $size = \mb_strlen($value);
                $errorMessage = $this->_('string should contain at most {max} characters but contains {size}', [
                    'max'  => $this->maxSize,
                    'size' => $size,
                ]);
                break;

            case is_array($value):
                $size = count($value);
                $errorMessage = $this->_('array should contain at most {max} items but contains {size}', [
                    'max'  => $this->maxSize,
                    'size' => $size,
                ]);
                break;

            default:
                throw new ValidatorException($this->_('checks only numbers, strings and arrays, got: {type}', [
                    'type' => gettype($value),
                ]));
        }

        if ($size > $this->maxSize) {
            throw new ValidatorException($errorMessage);
        }

        return $value;
    }

    public function getDescription(): string
    {
        return "Max count/size/length: {$this->maxSize}";
    }
}
