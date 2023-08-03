<?php

require_once __DIR__ . '/../var/vendor/autoload.php';

use OValidator\Config;
use OValidator\Engines\VInteger;
use OValidator\Form;
use OValidator\Interfaces\Localization;
use OValidator\Mapper;
use OValidator\Objects\State;
use OValidator\Setters\ReflectionSetter;

/**
 * Example of custom user defined Localization. VInteger contains localization key
 * NOT_NUMERIC. We can replace it with custom message
 */

class Input
{
    public int $myField;
}

$input = new Input();

class MyLoc implements Localization
{
    public function _(string $validatorClass, string $messageCode, array $replaces = []): string
    {
        if ($messageCode === 'NOT_NUMERIC') {
            return 'MY MESSAGE';
        }

        return 'OTHER MESSAGE';
    }
}

$config = (new Config())
    ->add('myField', 'Field', State::Required, [new VInteger()])
;

$form = (new Form())->fromArray([
    'myField' => 'BAD_NUMBER',
]);
$result = (new Mapper($form, $config, new MyLoc()))->toObject($input, new ReflectionSetter());

if ($result === null) {
    throw new \Exception('result should be not null in this example');
}

var_dump($result->getErrors());

/*

Output:

array(1) {
  ["myField"]=>
  array(1) {
    [0]=>
    string(10) "MY MESSAGE"
  }
}

*/
