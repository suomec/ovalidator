<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VMax;
use OValidator\Exceptions\EngineException;
use PHPUnit\Framework\TestCase;

class VMaxTest extends TestCase
{
    /**
     * @dataProvider providerMax
     */
    public function testMaxEngineSuccess(mixed $input, int $maxValue): void
    {
        $engine = new VMax($maxValue);

        $this->assertEquals($input, $engine->check($input));
    }

    public function testMaxEngineFailedIfIntegerToSmall(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('number must be less than 200');

        (new VMax(200))->check(300);
    }

    public function testMaxEngineFailedIfFloatToSmall(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('number must be less than 300');

        (new VMax(300))->check(400.0);
    }

    public function testMaxEngineFailedIfStringTooShort(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('string should contain at most 2 characters but contains 4');

        (new VMax(2))->check('test');
    }

    public function testMaxEngineFailedIfArrayToSmall(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('array should contain at most 2 items but contains 4');

        (new VMax(2))->check([1, 2, 3, 4]);
    }

    /**
     * @return array<mixed>
     */
    private function providerMax(): array
    {
        return [
            [100, 900],
            [100, 100],
            [100.1, 900],
            ['string', 900],
            ['首映鼓掌10分鐘 評語指不及', 900],
            [[3,2,1], 900],
        ];
    }
}
