<?php

declare(strict_types=1);

namespace OValidator\Tests;

use OValidator\Setters\DirectSetter;
use OValidator\Tests\Samples\SObject1;
use OValidator\Tests\Samples\SObject2;
use OValidator\Tests\Samples\SObject3;
use PHPUnit\Framework\TestCase;

class DirectSetterTest extends TestCase
{
    public function testDirectSetterFailsIfPropertyDoesntExists(): void
    {
        $o = new SObject3();

        $setter = new DirectSetter();
        $result = $setter->setProperties($o, [
            'prop' => 'value',
        ]);

        $this->assertNotNull($result);
        $this->assertEquals("property doesn't exists", $result->getErrors()['prop'][0]);
    }

    public function testDirectSetterFailsIfTypeMismatch(): void
    {
        $o = new SObject2();

        $setter = new DirectSetter();
        $result = $setter->setProperties($o, [
            'prop' => 'value',
        ]);

        $this->assertNotNull($result);
        $this->assertEquals("assign type mismatch", $result->getErrors()['prop'][0]);
    }

    public function testDirectSetterSuccess(): void
    {
        $o = new SObject1();

        $setter = new DirectSetter();
        $result = $setter->setProperties($o, [
            'pIntReq'  => 123,
            'pIntOpt1' => null,
            'pIntOpt2' => 234,
        ]);

        $this->assertNull($result);
        $this->assertEquals(123, $o->pIntReq);
        $this->assertEquals(null, $o->pIntOpt1);
        $this->assertEquals(234, $o->pIntOpt2);
    }
}
