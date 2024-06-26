<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VDateTime;
use OValidator\Exceptions\EngineException;
use OValidator\Objects\LocPhpFile;
use PHPUnit\Framework\TestCase;

class VDateTimeTest extends TestCase
{
    /**
     * @dataProvider providerDateTime
     */
    public function testBoolEngineSuccess(mixed $input, string $format, bool $asString, mixed $result): void
    {
        $engine = $this->get($format, $asString);

        $this->assertEquals($result, $engine->check($input));
    }

    public function testDateTimeEngineFailedIfFormatIncorrect(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage("can't parse date for format: Y-m-d");

        ($this->get('Y-m-d'))->check('0002-test');
    }

    public function testDateTimeEngineFailedIfDateNotGood(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage("can't parse date for format: Y-m-d");

        ($this->get('Y-m-d'))->check('2000-01-35');
    }

    public function testDateTimeEngineFailedIfInputTypeIncorrect(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('should be string or DateTimeImmutable');

        ($this->get('Y-m-d'))->check(123);
    }

    /**
     * @return array<mixed>
     */
    protected function providerDateTime(): array
    {
        return [
            ['2000-01-01', 'Y-m-d', true, '2000-01-01'],
            ['2000-01-01 00:01:01', 'Y-m-d H:i:s', false, new \DateTimeImmutable('2000-01-01 00:01:01')],
        ];
    }

    private function get(string $format, bool $asString = false): VDateTime
    {
        $l = new LocPhpFile(__DIR__ . '/../../etc/loc-en.php');

        $v = new VDateTime($format, $asString);
        $v->setLocalization($l);

        return $v;
    }
}
