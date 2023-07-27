<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

class VStringable implements \Stringable
{
    public function __toString(): string
    {
        return 'test';
    }
}
