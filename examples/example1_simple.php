<?php

require_once __DIR__ . '/../var/vendor/autoload.php';

use OValidator\Config;
use OValidator\Engines\VInteger;
use OValidator\Engines\VString;
use OValidator\Objects\State;
use OValidator\OValidator;

/**
 * Transforms several fields into object with properties of class Input
 */

class Input
{
    public int $int;
    public ?string $string;
    public float $float;
}

$input = new Input();

$config = (new Config())
    ->add('int', 'Int field', State::Required, [new VInteger()])
    ->add('string', 'String optional field', State::Optional, [new VString()])
    ->add('float', 'Float field', State::Required, [new \OValidator\Engines\VFloat()])
;

OValidator::validateAndSet($config, [
    'int'    => 100,
    'string' => 'test',
    'float'  => 1.2,
], $input);

var_dump($input);

/*

Output:

object(Input)#3 (3) {
  ["int"]=>
  int(100)
  ["string"]=>
  string(4) "test"
  ["float"]=>
  float(1.2)
}

*/
