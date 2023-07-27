<?php

declare(strict_types=1);

namespace OValidator\Tests;

use OValidator\Config;
use OValidator\Engines\VInteger;
use OValidator\Interfaces\Localization;
use OValidator\Objects\State;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testConfigFailsIfOneOfInputKeysNotString(): void
    {
        $config = new Config();

        $l = $this->createMock(Localization::class);

        //@phpstan-ignore-next-line
        $result = $config->validate([
            123 => 234,
        ], $l);

        $this->assertEquals(['unknown' => ['all input fields keys should be strings']], $result->getErrors());
    }

    public function testConfigFailsIfExtraFieldPassed(): void
    {
        $config = (new Config())->add('a', '', State::Required, [new VInteger()]);

        $l = $this->createMock(Localization::class);

        $result = $config->validate(['a' => 123, 'b' => 234], $l);

        $this->assertEquals(['b' => ['extra field not allowed']], $result->getErrors());
    }

    public function testConfigFailsIfFieldRequiredAndNotPassed(): void
    {
        $config = (new Config())->add('a', '', State::Required, [new VInteger()]);

        $l = $this->createMock(Localization::class);

        $result = $config->validate([], $l);

        $this->assertEquals(['a' => ['field is required but not passed']], $result->getErrors());
    }

    public function testConfigFailsIfFieldRequiredAndNull(): void
    {
        $config = (new Config())->add('a', '', State::Required, [new VInteger()]);

        $l = $this->createMock(Localization::class);

        $result = $config->validate(['a' => null], $l);

        $this->assertEquals(['a' => ['field is required but not passed']], $result->getErrors());
    }

    public function testConfigFailsOnValidationError(): void
    {
        $config = (new Config())->add('a', '', State::Required, [new VInteger()]);

        $l = $this->createMock(Localization::class);

        $result = $config->validate(['a' => 'asd'], $l);

        $this->assertTrue($result->hasErrors());
    }

    public function testConfigSuccess(): void
    {
        $config = (new Config())
            ->add('a', '', State::Required, [new VInteger()])
            ->add('b', '', State::Optional, [new VInteger()])
            ->add('c', '', State::Optional, [new VInteger()])
        ;

        $l = $this->createMock(Localization::class);

        $result = $config->validate([
            'a' => 1,
            'b' => null,
        ], $l);

        $this->assertFalse($result->hasErrors());
        $this->assertEquals([
            'a' => 1,
            'b' => null,
            'c' => null,
        ], $result->getValues());
    }
}
