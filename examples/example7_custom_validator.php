<?php

require_once __DIR__ . '/../var/vendor/autoload.php';

use OValidator\Config;
use OValidator\Exceptions\ValidationException;
use OValidator\Objects\State;
use OValidator\OValidator;

/**
 * Example of custom user defined validator. It's ok for 33, but fails on 34
 */

class Input
{
    public int $myField;
}

$input = new Input();

class OddValidator implements \OValidator\Interfaces\Validator
{
    public function check(mixed $value): mixed
    {
        if (!is_int($value)) {
            throw new ValidationException('should be integer');
        }

        if ($value % 2 === 1) {
            return $value;
        }

        throw new ValidationException('not odd');
    }

    public function setI18n(\OValidator\Interfaces\I18n $i18n): void
    {
    }

    public function getDescription(): string
    {
        return 'Check if value is odd';
    }
}

$config = (new Config())
    ->add('myField', 'Special field', State::Required, [new OddValidator()])
;

OValidator::validateAndSet($config, [
    'myField' => 33,
], $input);

var_dump($input);

/*

Output:

object(Input)#3 (1) {
  ["myField"]=>
  int(33)
}

*/
