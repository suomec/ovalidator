<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VEnum;
use OValidator\Exceptions\EngineException;
use PHPUnit\Framework\TestCase;

class VEnumTest extends TestCase
{
    /**
     * @dataProvider providerEnum
     * @param array<mixed> $disallow
     */
    public function testEnumEngineSuccess(mixed $input, array $disallow, mixed $result): void
    {
        $engine = new VEnum(VEnumObjectInt::class, $disallow);

        $this->assertEquals($result, $engine->check($input));
    }

    public function testEnumEngineFailedIfCaseNotFound(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('case not found in: Case1, Case3');

        (new VEnum(VEnumObjectInt::class, [VEnumObjectInt::Case2]))->check('Xxx');
    }

    public function testEnumEngineFailedIfCaseClassIncorrect(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('passed enum class name not exists');

        new VEnum(self::class);
    }

    /**
     * @return array<mixed>
     */
    private function providerEnum(): array
    {
        return [
            ['Case1', [], VEnumObjectInt::Case1],
            ['Case3', [], VEnumObjectInt::Case3],
        ];
    }
}
