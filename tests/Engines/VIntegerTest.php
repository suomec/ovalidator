<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VInteger;
use OValidator\Exceptions\EngineException;
use OValidator\Objects\LocPhpFile;
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
        $this->expectExceptionMessage('should be numeric string');

        ($this->get())->check([1, 2, 3]);
    }

    public function testIntegerEngineFailedIfFloat(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('floats not allowed');

        ($this->get())->check(1.1);
    }

    public function testIntegerEngineFailedIfFloatWithoutMantissa(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('seems to be float');

        ($this->get())->check('1.1');
    }

    /**
     * @return array<mixed>
     */
    protected function providerInteger(): array
    {
        return [
            [1, 1],
            [-1, -1],
            ['123', 123],
            ['+89', 89],
        ];
    }

    private function get(): VInteger
    {
        $l = new LocPhpFile(__DIR__ . '/../../etc/en.php');

        $v = new VInteger();
        $v->setLocalization($l);

        return $v;
    }
}
