<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\ValidatorException;
use OValidator\Objects\ValidatorBase;

/**
 * Min size/count of items
 */
class VMin extends ValidatorBase
{
    /** @var int Min value */
    private int $minSize;

    public function __construct(int $minSize)
    {
        $this->minSize = $minSize;
    }

    public function check(mixed $value): mixed
    {
        switch (true) {
            case is_float($value):
            case is_int($value):
                $size = $value;
                $errorMessage = $this->_('number must be greater than {min}', ['min' => $this->minSize]);
                break;

            case is_string($value):
                $size = \mb_strlen($value);
                $errorMessage = $this->_('string should contain at least {min} characters but contains {size}', [
                    'min'  => $this->minSize,
                    'size' => $size,
                ]);
                break;

            case is_array($value):
                $size = count($value);
                $errorMessage = $this->_('array should contain at least {min} items but contains {size}', [
                    'min'  => $this->minSize,
                    'size' => $size,
                ]);
                break;

            default:
                throw new ValidatorException($this->_('checks only numbers, strings and arrays, got: {type}', [
                    'type' => gettype($value),
                ]));
        }

        if ($size < $this->minSize) {
            throw new ValidatorException($errorMessage);
        }

        return $value;
    }

    public function getDescription(): string
    {
        return "Min count/size/length: {$this->minSize}";
    }
}
