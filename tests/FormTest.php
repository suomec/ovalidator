<?php

declare(strict_types=1);

namespace OValidator\Tests;

use OValidator\Form;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    public function testFormFailsIfKeyNotString(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('fromArray(): every key should be string');

        $f = new Form();
        //@phpstan-ignore-next-line
        $f->fromArray([1 => 1]);
    }

    public function testFormSuccess(): void
    {
        $in = ['a' => 1, 'b' => 2];

        $f = new Form();
        $f->fromArray($in);

        $this->assertEquals($in, $f->export());
    }
}
