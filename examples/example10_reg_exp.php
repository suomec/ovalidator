<?php

require_once __DIR__ . '/../var/vendor/autoload.php';

use OValidator\Config;
use OValidator\Engines\VRegExpMatch;
use OValidator\Engines\VString;
use OValidator\Objects\State;
use OValidator\OValidator;

/**
 * Check if matches regexp
 */

class Input
{
    public string $hash;
}

$input = new Input();

$config = (new Config())
    ->add('hash', 'Seems like md5-hash', State::Required, [
        new VString(true),
        new VRegExpMatch('|^[a-f\d]{32}$|', 'md5-hash')
    ])
;

OValidator::validateAndSet($config, [
    'hash' => md5('hello'),
], $input);

var_dump($input);

/*

Output:

object(Input)#3 (1) {
  ["hash"]=>
  string(32) "5d41402abc4b2a76b9719d911017c592"
}

*/
