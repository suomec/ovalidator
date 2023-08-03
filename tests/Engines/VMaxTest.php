<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VMax;
use OValidator\Exceptions\EngineException;
use OValidator\Objects\LocPhpFile;
use PHPUnit\Framework\TestCase;

class VMaxTest extends TestCase
{
    /**
     * @dataProvider providerMax
     */
    public function testMaxEngineSuccess(mixed $input, int $maxValue): void
    {
        $engine = $this->get($maxValue);

        $this->assertEquals($input, $engine->check($input));
    }

    public function testMaxEngineFailedIfIntegerToSmall(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('number must be less than 200');

        ($this->get(200))->check(300);
    }

    public function testMaxEngineFailedIfFloatToSmall(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('number must be less than 300');

        ($this->get(300))->check(400.0);
    }

    public function testMaxEngineFailedIfStringTooShort(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('string should contain at most 2 characters but contains 4');

        ($this->get(2))->check('test');
    }

    public function testMaxEngineFailedIfArrayToSmall(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('array should contain at most 2 items but contains 4');

        ($this->get(2))->check([1, 2, 3, 4]);
    }

    /**
     * @return array<mixed>
     */
    protected function providerMax(): array
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

    private function get(int $max): VMax
    {
        $l = new LocPhpFile(__DIR__ . '/../../etc/loc-en.php');

        $v = new VMax($max);
        $v->setLocalization($l);

        return $v;
    }
}
