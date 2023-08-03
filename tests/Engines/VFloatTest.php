<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VFloat;
use OValidator\Exceptions\EngineException;
use OValidator\Objects\LocPhpFile;
use PHPUnit\Framework\TestCase;

class VFloatTest extends TestCase
{
    /**
     * @dataProvider providerFloat
     */
    public function testFloatEngineSuccess(mixed $input, mixed $result): void
    {
        $engine = new VFloat();

        $this->assertEquals($result, $engine->check($input));
    }

    public function testFloatEngineFailedIfNotFloat(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('is not float');

        ($this->get())->check([100]);
    }

    /**
     * @return array<mixed>
     */
    protected function providerFloat(): array
    {
        return [
            [1.1, 1.1],
            ['200.1', 200.1],
            [-1.1, -1.1],
        ];
    }

    private function get(): VFloat
    {
        $l = new LocPhpFile(__DIR__ . '/../../etc/loc-en.php');

        $v = new VFloat();
        $v->setLocalization($l);

        return $v;
    }
}
