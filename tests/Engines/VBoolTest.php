<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VBool;
use OValidator\Exceptions\EngineException;
use OValidator\Objects\LocPhpFile;
use PHPUnit\Framework\TestCase;

class VBoolTest extends TestCase
{
    /**
     * @dataProvider providerBool
     */
    public function testBoolEngineSuccess(mixed $input, bool $result): void
    {
        $engine = $this->get();

        $this->assertEquals($result, $engine->check($input));
    }

    public function testBoolEngineFailed(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('boolean format not parsed');

        ($this->get())->check('test string');
    }

    /**
     * @return array<mixed>
     */
    private function providerBool(): array
    {
        return [
            ['truE', true],
            ['1', true],
            [1, true],
            [true, true],
            ['YES', true],
            ['on', true],

            ['faLse', false],
            ['0', false],
            [0, false],
            [false, false],
            ['no', false],
            ['OFF', false],
        ];
    }

    private function get(): VBool
    {
        $l = new LocPhpFile(__DIR__ . '/../../etc/en.php');

        $v = new VBool();
        $v->setLocalization($l);

        return $v;
    }
}
