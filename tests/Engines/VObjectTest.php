<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VObject;
use OValidator\Exceptions\EngineException;
use PHPUnit\Framework\TestCase;

class VObjectTest extends TestCase
{
    /**
     * @dataProvider providerObject
     * @param array<string, mixed> $fields
     */
    public function testObjectEngineSuccess(string $class, mixed $input, array $fields): void
    {
        $engine = new VObject($class);

        $result = $engine->check($input);

        foreach ($fields as $k => $v) {
            $this->assertEquals($v, $result->$k);
        }
    }

    public function testObjectEngineFailedIfClassDoesntImplementInterface(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('OValidator\Tests\Engines\VObjectTest should implement CanBeValidated interface');

        new VObject(VObjectTest::class);
    }

    public function testObjectEngineFailedIfInputNotArray(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('value should be array');

        (new VObject(VObjectClassOk::class))->check('test');
    }

    public function testObjectEngineFailedIfValidationFailed(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('object validation error[a: field is required but not passed]');

        (new VObject(VObjectClassOk::class))->check(['x' => 100]);
    }

    /**
     * @return array<mixed>
     */
    protected function providerObject(): array
    {
        return [
            [VObjectClassOk::class, ['a' => 1], ['a' => true]],
        ];
    }
}
