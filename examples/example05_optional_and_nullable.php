<?php

require_once __DIR__ . '/../var/vendor/autoload.php';

use OValidator\Config;
use OValidator\Engines\VInteger;
use OValidator\Objects\State;
use OValidator\OValidator;

/**
 * Example with optional and nullable fields
 */

class Input
{
    public int $intRequired;
    public ?int $intOptionalFirst;
    public ?int $intOptionalSecond;
}

$input = new Input();

$config = (new Config())
    ->add('intRequired', 'Required', State::Required, [new VInteger()])
    ->add('intOptionalFirst', 'Optional', State::Optional, [new VInteger()])
    ->add('intOptionalSecond', 'Optional', State::Optional, [new VInteger()])
;

OValidator::validateAndSet($config, [
    'intRequired'      => 1,
    'intOptionalFirst' => null,
    // intOptionalSecond not passed but will be set to NULL
], $input);

var_dump($input);

/*

Output:

object(Input)#3 (3) {
  ["intRequired"]=>
  int(1)
  ["intOptionalFirst"]=>
  NULL
  ["intOptionalSecond"]=>
  NULL
}

*/
