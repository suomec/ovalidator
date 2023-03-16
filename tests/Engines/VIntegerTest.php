<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VInteger;
use OValidator\Exceptions\EngineException;
use PHPUnit\Framework\TestCase;

class VIntegerTest extends TestCase
{
    /**
     * @dataProvider providerInteger
     */
    public function testIntegerEngineSuccess(mixed $input, mixed $result): void
    {
        $engine = new VInteger();

        $this->assertEquals($result, $engine->check($input));
    }

    public function testIntegerEngineFailedIfNotNumeric(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('not numeric');

        (new VInteger())->check([1, 2, 3]);
    }

    public function testIntegerEngineFailedIfFloat(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('is float');

        (new VInteger())->check(1.1);
    }

    public function testIntegerEngineFailedIfFloatWithoutMantissa(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('may be float');

        (new VInteger())->check('1.1');
    }

    /**
     * @return array<mixed>
     */
    private function providerInteger(): array
    {
        return [
            [1, 1],
            [-1, -1],
            ['123', 123],
            ['+89', 89],
        ];
    }
}
