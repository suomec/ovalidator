<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
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
                $errorMessage = $this->_('NUM_ERROR', ['min' => $this->minSize]);
                break;

            case is_string($value):
                $size = \mb_strlen($value);
                $errorMessage = $this->_('STRING_ERROR', [
                    'min'  => $this->minSize,
                    'size' => $size,
                ]);
                break;

            case is_array($value):
                $size = count($value);
                $errorMessage = $this->_('ARRAY_ERROR', [
                    'min'  => $this->minSize,
                    'size' => $size,
                ]);
                break;

            default:
                throw new EngineException($this->_('TYPE_ERROR', [
                    'type' => gettype($value),
                ]));
        }

        if ($size < $this->minSize) {
            throw new EngineException($errorMessage);
        }

        return $value;
    }

    public function getDescription(): string
    {
        return "Min value/length: {$this->minSize}";
    }
}
