<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VArrayFromString;
use OValidator\Exceptions\EngineException;
use OValidator\Objects\LocPhpFile;
use PHPUnit\Framework\TestCase;

class VArrayFromStringTest extends TestCase
{
    /**
     * @dataProvider providerArrayFromString
     * @param array<mixed> $result
     */
    public function testArrayFromStringEngineSuccess(mixed $input, string $separator, bool $excludeEmpty, array $result): void
    {
        $engine = $this->get($separator, $excludeEmpty);

        $this->assertEquals($result, $engine->check($input));
    }

    public function testArrayFromStringEngineFailedForEmptySeparator(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("separator can't be empty");

        ($this->get(''))->check('test');
    }

    public function testArrayFromStringEngineFailedIfInputNotString(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('value should be string');

        ($this->get())->check(['x']);
    }

    /**
     * @return array<mixed>
     */
    protected function providerArrayFromString(): array
    {
        return [
            ['1,2,3', ',', true, ['1', '2', '3']],
            ['', '||', true, []],
            ['test value without separator', '||', true, ['test value without separator']],
            ['1|2||', '|', true, ['1', '2']],
            ['1|2||', '|', false, ['1', '2', '', '']],
        ];
    }

    private function get(string $separator = ',', bool $excludeEmpty = true): VArrayFromString
    {
        $l = new LocPhpFile(__DIR__ . '/../../etc/loc-en.php');

        $v = new VArrayFromString($separator, $excludeEmpty);
        $v->setLocalization($l);

        return $v;
    }
}
