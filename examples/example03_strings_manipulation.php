<?php

require_once __DIR__ . '/../var/vendor/autoload.php';

use OValidator\Config;
use OValidator\Engines\VArray;
use OValidator\Engines\VArrayFromString;
use OValidator\Engines\VCallback;
use OValidator\Engines\VInteger;
use OValidator\Objects\State;
use OValidator\OValidator;

/**
 * Transforms '1, 2, 3, 3, 3, 4' to [2, 4, 6, 8]
 */

class Input
{
    /** @var int[] */
    public array $list;
}

$input = new Input();

$config = (new Config())
    ->add('list', 'Comma separated input', State::Required, [
        new VArrayFromString(','),
        new VArray([
            new VInteger(),
            new VCallback(function (int $input) {
                return $input*2;
            }),
        ], true),
    ])
;

OValidator::validateAndSet($config, [
    'list' => '1, 2, 3, 3, 3, 4',
], $input);

var_dump($input);

/*

Output:

object(Input)#3 (1) {
  ["list"]=>
  array(4) {
    [0]=>
    int(2)
    [1]=>
    int(4)
    [2]=>
    int(6)
    [3]=>
    int(8)
  }
}

*/
