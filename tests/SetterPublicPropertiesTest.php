<?php

declare(strict_types=1);

namespace OValidator\Tests;

use OValidator\Setters\PublicProperties;
use OValidator\Tests\Samples\SObject1;
use OValidator\Tests\Samples\SObject2;
use OValidator\Tests\Samples\SObject3;
use PHPUnit\Framework\TestCase;

class SetterPublicPropertiesTest extends TestCase
{
    public function testPublicPropertiesSuccess(): void
    {
        $v = new SObject1();

        $pObjProp = new SObject2();
        $pObjProp->prop = 111;

        $setter = new PublicProperties();
        $r = $setter->setProperties($v, [
            // int values
            'pIntReq' => 123,
            'pIntOpt1' => null,
            'pIntOpt2' => 123,
            // pIntOpt3 not passed, will be set to NULL because it's nullable

            // string values
            'pStrReq' => 'test',
            'pStrOpt1' => null,
            'pStrOpt2' => 'test',
            // pStrOpt3 not passed, will be set to NULL because it's nullable

            // float
            'pFloatReq' => 1.1,
            'pFloatOpt' => null,

            // bool
            'pBoolReq' => true,
            'pBoolOpt' => false,

            // without type
            'pNoType' => 'test',

            // object with class
            'pStdClass' => (object)['a' => 'b'],
            'pObjProp' => $pObjProp,
            'pObjPropInt' => new SObject3(),
        ]);

        $this->assertNull($r);

        $this->assertEquals(123, $v->pIntReq);
        $this->assertEquals(null, $v->pIntOpt1);
        $this->assertEquals(123, $v->pIntOpt2);
        $this->assertEquals(null, $v->pIntOpt3);

        $this->assertEquals('test', $v->pStrReq);
        $this->assertEquals(null, $v->pStrOpt1);
        $this->assertEquals('test', $v->pStrOpt2);
        $this->assertEquals(null, $v->pStrOpt3);

        $this->assertEquals(1.1, $v->pFloatReq);
        $this->assertEquals(null, $v->pFloatOpt);

        $this->assertEquals(true, $v->pBoolReq);
        $this->assertEquals(false, $v->pBoolOpt);

        $this->assertEquals('test', $v->pNoType);
        $this->assertEquals((object)['a' => 'b'], $v->pStdClass);
        $this->assertEquals(111, $v->pObjProp->prop);
        $this->assertInstanceOf(SObject3::class, $v->pObjPropInt);
    }
}
