<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VSkip;
use PHPUnit\Framework\TestCase;

class VSkipTest extends TestCase
{
    public function testSkipEngineSuccess(): void
    {
        $result = (new VSkip())->check('test');

        $this->assertEquals('test', $result);
    }
}
