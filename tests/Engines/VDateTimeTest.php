<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VDateTime;
use OValidator\Exceptions\EngineException;
use PHPUnit\Framework\TestCase;

class VDateTimeTest extends TestCase
{
    /**
     * @dataProvider providerDateTime
     */
    public function testBoolEngineSuccess(mixed $input, string $format, bool $asString, mixed $result): void
    {
        $engine = new VDateTime($format, $asString);

        $this->assertEquals($result, $engine->check($input));
    }

    public function testDateTimeEngineFailedIfFormatIncorrect(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage("can't parse date for format: Y-m-d");

        (new VDateTime('Y-m-d'))->check('0002-test');
    }

    public function testDateTimeEngineFailedIfInputTypeIncorrect(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('should be string or DateTimeImmutable');

        (new VDateTime('Y-m-d'))->check(123);
    }

    /**
     * @return array<mixed>
     */
    private function providerDateTime(): array
    {
        return [
            ['2000-01-01', 'Y-m-d', true, '2000-01-01'],
            ['2000-01-01 00:01:01', 'Y-m-d H:i:s', false, new \DateTimeImmutable('2000-01-01 00:01:01')],
        ];
    }
}
