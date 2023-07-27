<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VString;
use OValidator\Exceptions\EngineException;
use OValidator\Objects\LocPhpFile;
use PHPUnit\Framework\TestCase;

class VStringTest extends TestCase
{
    /**
     * @dataProvider providerString
     */
    public function testStringEngineSuccess(mixed $input, bool $trim, string $result): void
    {
        $engine = $this->get($trim);

        $this->assertEquals($result, $engine->check($input));
    }

    public function testStringEngineFailedIfBadArgumentType(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('should be string');

        ($this->get())->check(new \stdClass());
    }

    /**
     * @return array<mixed>
     */
    public function providerString(): array
    {
        return [
            [' input ', true, 'input'],
            [' input ', false, ' input '],
            [new VStringable(), false, 'test'],
            [123, false, '123'],
        ];
    }

    private function get(bool $trim = false): VString
    {
        $l = new LocPhpFile(__DIR__ . '/../../etc/en.php');

        $v = new VString($trim);
        $v->setLocalization($l);

        return $v;
    }
}
