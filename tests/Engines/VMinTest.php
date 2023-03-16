<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VMin;
use OValidator\Exceptions\EngineException;
use PHPUnit\Framework\TestCase;

class VMinTest extends TestCase
{
    /**
     * @dataProvider providerMin
     */
    public function testMinEngineSuccess(mixed $input, int $minValue): void
    {
        $engine = new VMin($minValue);

        $this->assertEquals($input, $engine->check($input));
    }

    public function testMinEngineFailedIfIntegerToBig(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('number must be greater than 200');

        (new VMin(200))->check(100);
    }

    public function testMinEngineFailedIfFloatToBig(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('number must be greater than 300');

        (new VMin(300))->check(100.0);
    }

    public function testMinEngineFailedIfStringTooLong(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('string should contain at least 9 characters but contains 4');

        (new VMin(9))->check('test');
    }

    public function testMinEngineFailedIfArrayToBig(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('array should contain at least 3 items but contains 2');

        (new VMin(3))->check([1, 2]);
    }

    /**
     * @return array<mixed>
     */
    private function providerMin(): array
    {
        return [
            [100, 1],
            [100, 100],
            [100.1, 1],
            ['string', 1],
            ['首映鼓掌10分鐘 評語指不及', 1],
            [[3,2,1], 1],
        ];
    }
}
