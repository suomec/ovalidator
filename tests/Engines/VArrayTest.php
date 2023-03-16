<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VArray;
use OValidator\Engines\VInteger;
use OValidator\Exceptions\EngineException;
use OValidator\Interfaces\Validator;
use PHPUnit\Framework\TestCase;

class VArrayTest extends TestCase
{
    /**
     * @dataProvider providerArray
     * @param ?Validator[] $validators
     */
    public function testArrayEngineSuccess(mixed $input, ?array $validators, bool $onlyUnique, bool $keepOriginal, mixed $result): void
    {
        $engine = new VArray($validators, $onlyUnique, $keepOriginal);

        $this->assertEquals($result, $engine->check($input));
    }

    public function testArrayEngineFailedIfValidatorsPassedButEmptyList(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('should be at least one validator for array item (validators != null)');

        (new VArray([]))->check([]);
    }

    public function testArrayEngineFailedIfValidatorsPassedButTypeIncorrect(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('every validator should be instance of Validator interface');


        //@phpstan-ignore-next-line
        (new VArray([new VInteger(), new \stdClass()]))->check([1]);
    }

    public function testArrayEngineFailedIfNotArrayPassed(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('should be array');

        (new VArray())->check('test');
    }

    /**
     * @return array<mixed>
     */
    private function providerArray(): array
    {
        return [
            // unique
            [
                [1, 2, 3, 1], null, true, true, [1, 2, 3]
            ],
            [
                [1, 2, 3, 1], null, false, true, [1, 2, 3, 1]
            ],
            // original
            [
                ['x' => 1, 'y' => 2], null, true, false, [1, 2]
            ],
            [
                ['x' => 1, 'y' => 2], null, false, true, ['x' => 1, 'y' => 2]
            ],
            // validators
            [
                ['111'], [new VInteger()], false, true, [111]
            ],
        ];
    }
}
