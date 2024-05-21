<?php

require_once __DIR__ . '/../var/vendor/autoload.php';

use OValidator\Config;
use OValidator\Engines\VArray;
use OValidator\Engines\VArrayFromJson;
use OValidator\Engines\VArrayFromString;
use OValidator\Engines\VCallback;
use OValidator\Objects\State;
use OValidator\OValidator;

/**
 * Complex JSON transformations
 */

class Input
{
    /** @var int[] */
    public array $array;
}

$input = new Input();

$config = (new Config())
    ->add('array', 'Complex', State::Required, [
        new VArrayFromString('|'),
        new VArray([
            new VArrayFromJson(),
            new VCallback(function (array $v) {
                return $v['a'] * 2;
            }),
        ]),
    ])
;

OValidator::validateAndSet($config, [
    'array' => '{"a": 1}|{"a": 2}|{"a": 3}|{"a": 4}',
], $input);

var_dump($input);

/*

Output:

object(Input)#3 (1) {
  ["array"]=>
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
