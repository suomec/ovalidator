<?php

declare(strict_types=1);

namespace OValidator\Tests;

use OValidator\Config;
use OValidator\Engines\VInteger;
use OValidator\Form;
use OValidator\Interfaces\Localization;
use OValidator\Interfaces\Setter;
use OValidator\Mapper;
use OValidator\Objects\Result;
use OValidator\Objects\State;
use OValidator\Setters\ReflectionSetter;
use OValidator\Tests\Samples\SObject2;
use PHPUnit\Framework\TestCase;

class MapperTest extends TestCase
{
    public function testMapperFailsIfValidationFailed(): void
    {
        $form = (new Form())->fromArray(['prop' => 'test']);
        $config = (new Config())->add('prop', '', State::Required, [new VInteger()]);

        $l = $this->createMock(Localization::class);
        $l->method('_')->willReturn('TEST');

        $mapper = new Mapper($form, $config, $l);

        $setter = new ReflectionSetter();
        $object = new SObject2();
        $object->prop = 1;
        $result = $mapper->toObject($object, $setter);

        if ($result === null) {
            $this->fail('result is null');
        }

        $this->assertTrue($result->hasErrors());
        $this->assertEquals(['prop' => ['TEST']], $result->getErrors());
    }

    public function testMapperFailsIfSetterFailed(): void
    {
        $form = (new Form())->fromArray(['prop' => 222]);
        $config = (new Config())->add('prop', '', State::Required, [new VInteger()]);

        $l = $this->createMock(Localization::class);

        $mapper = new Mapper($form, $config, $l);

        $badResult = new Result();
        $badResult->addError('prop', 'test');
        $setter = $this->createMock(Setter::class);
        $setter->method('setProperties')->willReturn($badResult);

        $object = new SObject2();
        $object->prop = 1;
        $result = $mapper->toObject($object, $setter);

        if ($result === null) {
            $this->fail('result is null');
        }

        $this->assertTrue($result->hasErrors());
        $this->assertEquals(['prop' => ['test']], $result->getErrors());
    }

    public function testMapperSuccess(): void
    {
        $form = (new Form())->fromArray(['prop' => 2]);
        $config = (new Config())->add('prop', '', State::Required, [new VInteger()]);

        $l = $this->createMock(Localization::class);

        $mapper = new Mapper($form, $config, $l);

        $setter = new ReflectionSetter();
        $object = new SObject2();
        $object->prop = 1;
        $result = $mapper->toObject($object, $setter);

        $this->assertNull($result);
        $this->assertEquals(2, $object->prop);
    }
}
