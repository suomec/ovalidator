<?php

require_once __DIR__ . '/../var/vendor/autoload.php';

use OValidator\Config;
use OValidator\Engines\VInteger;
use OValidator\Engines\VMin;
use OValidator\Interfaces\Collection;
use OValidator\Objects\State;
use OValidator\OValidator;

/**
 * Example with validation collection. Check if property ID is integer and more than zero
 * Collections help to reduce code duplication
 */

class Input
{
    public int $id;
}

$input = new Input();

class AutoIncrementValueCheck implements Collection
{
    public function getValidators(): array
    {
        return [
            new VInteger(),
            new VMin(1),
        ];
    }
}

$config = (new Config())
    ->addCollection('id', 'Collection field', State::Required, new AutoIncrementValueCheck())
;

OValidator::validateAndSet($config, [
    'id' => 100,
], $input);

var_dump($input);

/*

Output:

object(Input)#3 (1) {
  ["id"]=>
  int(100)
}


*/
