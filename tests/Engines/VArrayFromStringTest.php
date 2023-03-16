<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VArrayFromString;
use OValidator\Exceptions\EngineException;
use PHPUnit\Framework\TestCase;

class VArrayFromStringTest extends TestCase
{
    /**
     * @dataProvider providerArrayFromString
     * @param array<mixed> $result
     */
    public function testArrayFromStringEngineSuccess(mixed $input, string $separator, array $result): void
    {
        $engine = new VArrayFromString($separator);

        $this->assertEquals($result, $engine->check($input));
    }

    public function testArrayFromStringEngineFailedForEmptySeparator(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("separator can't be empty");

        (new VArrayFromString(''))->check('test');
    }

    public function testArrayFromStringEngineFailedIfInputNotString(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('value should be string');

        (new VArrayFromString())->check(['x']);
    }

    /**
     * @return array<mixed>
     */
    protected function providerArrayFromString(): array
    {
        return [
            ['1,2,3', ',', ['1', '2', '3']],
            ['', '||', []],
            ['test value without separator', '||', ['test value without separator']],
        ];
    }
}
