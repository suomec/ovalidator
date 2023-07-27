<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Objects\ValidatorBase;

/**
 * User-defined function applied to input. Returns result of function call on input value
 */
class VCallback extends ValidatorBase
{
    private \Closure $mapFunction;

    /**
     * @param callable $mapFunction Callable to apply for input argument
     */
    public function __construct(callable $mapFunction)
    {
        $this->mapFunction = $mapFunction(...);
    }

    public function check(mixed $value): mixed
    {
        return call_user_func($this->mapFunction, $value);
    }

    public function getDescription(): string
    {
        return 'user-defined callback';
    }
}
