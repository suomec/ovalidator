<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VInSet;
use OValidator\Exceptions\EngineException;
use PHPUnit\Framework\TestCase;

class VInSetTest extends TestCase
{
    /**
     * @dataProvider providerInSet
     * @param array<int, float|int|string> $allowed
     */
    public function testInSetEngineSuccess(mixed $input, array $allowed, bool $retIdx, mixed $result): void
    {
        $engine = new VInSet($allowed, $retIdx);

        $this->assertEquals($result, $engine->check($input));
    }

    public function testInSetEngineFailedIfInputTypeIncorrect(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('value type should be: string, int, float');

        (new VInSet(['aaa']))->check(new \stdClass());
    }

    public function testInSetEngineFailedIfInputValueNotAllowed(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('value is not allowed (not in set)');

        (new VInSet(['aaa']))->check('bbb');
    }

    /**
     * @return array<mixed>
     */
    public function providerInSet(): array
    {
        return [
            ['a', ['a', 'b', 'c'], false, 'a'],
            [1, [3, 2, 1], false, 1],
            [1.1, [3.1, 2.1, 1.1], false, 1.1],
            ['a', ['aa' => 'a', 'bb' => 'b', 'cc' => 'c'], true, 'aa'],
        ];
    }
}
