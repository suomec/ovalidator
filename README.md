# PHP object mapper and schema validator

This package created for mapping user input to pre-defined objects with validation schema (custom rules).

You can find more examples at the /examples/ dir.

Usually you have user input as array and need to map/validate it to your classes. This package can help you

First, you need a Form - class, that contains user raw data. It's /src/Form.php class.

```php

$form = (new Form())->fromArray([
    'some user input' => 'with string keys and mixed values',
    'other' => 100,
]);

```

Second, you need an object, with internal properties. It's just an instance of *your* class.

```php
class Input
{
    public int $field;
}

$input = new Input();
```

Third, you need a set of rules to map input to that class. It's a Config. Rules are set of ->add() methods

```php

$config = (new Config())
    ->add('field', 'Some description', State::Required, [
        new VInteger(),
        new VMin(10),
        new VMax(20),
    ])
;

```

And the last - some code for validation and map

```php

$result = (new Mapper($form, $config))->toObject($input, new PublicProperties());

```

If you have an input: `['field' => 15]` it's correct and `$input->field` will have a value of `15`. But for
input `['field' => 999]` `$result` variable will contain a list of errors (VMax check fails, because 999 > 20).
