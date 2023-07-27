<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VEnum;
use OValidator\Exceptions\EngineException;
use OValidator\Objects\LocPhpFile;
use PHPUnit\Framework\TestCase;

class VEnumTest extends TestCase
{
    /**
     * @dataProvider providerEnum
     * @param array<mixed> $disallow
     */
    public function testEnumEngineSuccess(mixed $input, array $disallow, mixed $result): void
    {
        $engine = $this->get(VEnumObjectInt::class, $disallow);

        $this->assertEquals($result, $engine->check($input));
    }

    public function testEnumEngineFailedIfCaseClassIncorrect(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('passed enum class name not exists');

        $this->get(self::class);
    }

    public function testEnumEngineFailedIfCaseNotFound(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('case not found in: Case1, Case3');

        ($this->get(VEnumObjectInt::class, [VEnumObjectInt::Case2]))->check('Xxx');
    }

    /**
     * @return array<mixed>
     */
    protected function providerEnum(): array
    {
        return [
            ['Case1', [], VEnumObjectInt::Case1],
            ['Case3', [], VEnumObjectInt::Case3],
        ];
    }

    //@phpstan-ignore-next-line
    private function get(string $class, array $disallow = []): VEnum
    {
        $l = new LocPhpFile(__DIR__ . '/../../etc/en.php');

        $v = new VEnum($class, $disallow);
        $v->setLocalization($l);

        return $v;
    }
}
