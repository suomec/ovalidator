<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VArrayFromJson;
use PHPUnit\Framework\TestCase;

class VArrayFromJsonTest extends TestCase
{
    /**
     * @dataProvider providerArrayFromJson
     * @param array<mixed> $result
     */
    public function testArrayFromJsonEngineSuccess(mixed $input, array $result): void
    {
        $engine = new VArrayFromJson();

        $this->assertEquals($result, $engine->check($input));
    }

    public function testArrayFromJsonEngineFailedIfInputValueNotString(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('value should be string');

        (new VArrayFromJson())->check([]);
    }

    public function testArrayFromJsonEngineFailedIfJsonIncorrect(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('value should be JSON-array');

        (new VArrayFromJson())->check('{{{');
    }

    /**
     * @return array<mixed>
     */
    private function providerArrayFromJson(): array
    {
        return [
            ['[1,2,3]', [1,2,3]],
            ['{"a":1,"b":2}', ['a' => 1, 'b' => 2]],
        ];
    }
}
