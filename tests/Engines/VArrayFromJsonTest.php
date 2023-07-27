<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VArrayFromJson;
use OValidator\Objects\LocPhpFile;
use PHPUnit\Framework\TestCase;

class VArrayFromJsonTest extends TestCase
{
    /**
     * @dataProvider providerArrayFromJson
     * @param array<mixed> $result
     */
    public function testArrayFromJsonEngineSuccess(mixed $input, array $result): void
    {
        $engine = $this->get();

        $this->assertEquals($result, $engine->check($input));
    }

    public function testArrayFromJsonEngineFailedIfDepthTooSmall(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('depth should be more than 0');

        ($this->get(-1))->check('{}');
    }

    public function testArrayFromJsonEngineFailedIfInputValueNotString(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('value should be string');

        ($this->get())->check([]);
    }

    public function testArrayFromJsonEngineFailedIfJsonIncorrect(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('value should be JSON-array');

        ($this->get())->check('{{{');
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

    private function get(int $depth = 100, int $flags = 0): VArrayFromJson
    {
        $l = new LocPhpFile(__DIR__ . '/../../etc/en.php');

        $v = new VArrayFromJson($depth, $flags);
        $v->setLocalization($l);

        return $v;
    }
}
