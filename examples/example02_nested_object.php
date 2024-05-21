<?php

require_once __DIR__ . '/../var/vendor/autoload.php';

use OValidator\Config;
use OValidator\Engines\VInteger;
use OValidator\Engines\VObject;
use OValidator\Interfaces\CanBeValidated;
use OValidator\Objects\State;
use OValidator\OValidator;

/**
 * Transforms array to typed php-object
 */

class Nested implements CanBeValidated
{
    public int $field;

    public function getValidationConfig(): \OValidator\Interfaces\Config
    {
        return (new Config())
            ->add('field', 'Int field', State::Required, [new VInteger()])
        ;
    }
}

class Input
{
    public Nested $nested;
}

$input = new Input();

$config = (new Config())
    ->add('nested', 'Nested field', State::Required, [
        new VObject(Nested::class),
    ])
;

OValidator::validateAndSet($config, [
    'nested' => [
        'field' => 123,
    ],
], $input);

var_dump($input);

/*

Output:

object(Input)#3 (1) {
  ["nested"]=>
  object(Nested)#12 (1) {
    ["field"]=>
    int(123)
  }
}

*/
