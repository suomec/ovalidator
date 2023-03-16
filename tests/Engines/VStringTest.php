<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VString;
use OValidator\Exceptions\EngineException;
use PHPUnit\Framework\TestCase;

class VStringTest extends TestCase
{
    /**
     * @dataProvider providerString
     */
    public function testStringEngineSuccess(mixed $input, bool $trim, string $result): void
    {
        $engine = new VString($trim);

        $this->assertEquals($result, $engine->check($input));
    }

    public function testStringEngineFailedIfBadArgumentType(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('should be string');

        (new VString())->check(123);
    }

    /**
     * @return array<mixed>
     */
    public function providerString(): array
    {
        return [
            [' input ', true, 'input'],
            [' input ', false, ' input '],
        ];
    }
}
