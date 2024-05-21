<?php

require_once __DIR__ . '/../var/vendor/autoload.php';

use OValidator\Config;
use OValidator\Engines\VArray;
use OValidator\Engines\VArrayFromJson;
use OValidator\Engines\VInteger;
use OValidator\Engines\VObject;
use OValidator\Interfaces\CanBeValidated;
use OValidator\Objects\State;
use OValidator\OValidator;

/**
 * Transforms JSON-object to php-class with complex typed fields
 */

class Complex implements CanBeValidated
{
    public int $a;

    /** @var int[] */
    public array $b;

    public SubComplex $c;

    public function getValidationConfig(): \OValidator\Interfaces\Config
    {
        return (new Config())
            ->add('a', '', State::Required, [new VInteger()])
            ->add('b', '', State::Required, [new VArray([new VInteger()])])
            ->add('c', '', State::Required, [new VObject(SubComplex::class)])
        ;
    }
}

class SubComplex implements CanBeValidated
{
    public int $d;

    public function getValidationConfig(): \OValidator\Interfaces\Config
    {
        return (new Config())
            ->add('d', '', State::Required, [new VInteger()])
        ;
    }
}

class Input
{
    public Complex $complex;
}

$input = new Input();

$config = (new Config())
    ->add('complex', 'JSON field', State::Required, [
        new VArrayFromJson(),
        new VObject(Complex::class),
    ])
;

OValidator::validateAndSet($config, [
    'complex' => '{"a": 123, "b": [1, 2, 3], "c": {"d": 456}}',
], $input);

var_dump($input);

/*

Output:

object(Input)#3 (1) {
  ["complex"]=>
  object(Complex)#13 (3) {
    ["a"]=>
    int(123)
    ["b"]=>
    array(3) {
      [0]=>
      int(1)
      [1]=>
      int(2)
      [2]=>
      int(3)
    }
    ["c"]=>
    object(SubComplex)#26 (1) {
      ["d"]=>
      int(456)
    }
  }
}

*/
