<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VCallback;
use PHPUnit\Framework\TestCase;

class VCallbackTest extends TestCase
{
    public function testCallbackEngineSuccess(): void
    {
        $result = (new VCallback(function (int $in) {
            return [$in, $in * 2];
        }))->check(4);

        $this->assertEquals([4, 8], $result);
    }
}
