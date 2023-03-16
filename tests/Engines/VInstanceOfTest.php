<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VInstanceOf;
use OValidator\Exceptions\EngineException;
use PHPUnit\Framework\TestCase;

class VInstanceOfTest extends TestCase
{
    /**
     * @dataProvider providerInstanceOf
     */
    public function testInstanceOfEngineSuccess(mixed $input, string $type): void
    {
        $engine = new VInstanceOf($type);

        $this->assertEquals($input, $engine->check($input));
    }

    public function testInstanceOfEngineFailedIfBadClass(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('should be instance of stdClass');

        (new VInstanceOf('stdClass'))->check('test');
    }

    /**
     * @return array<mixed>
     */
    private function providerInstanceOf(): array
    {
        return [
            [(object)['a' => 'b'], 'stdClass'],
        ];
    }
}
