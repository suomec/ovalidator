# PHP object mapper and schema validator (PHP version >= 8.1)

This package created for mapping user input to pre-defined objects with validation schema (custom rules).

Usually you have user input as array and need to map/validate it to your value-objects. This package can help you.

First, you need a Form - class, that contains user raw data. It's /src/Form.php class.

```php

$form = (new Form())->fromArray([
    'field_1' => 'any value',
    'field_2' => 15,
]);

```

Second, you need an object, with internal properties. It's just an instance of *your* class.

```php
class Input
{
    public int $field_2;
}

$input = new Input();
```

Third, you need a set of rules to map input to that class. It's a Config. Rules are set of ->add() methods

```php

$config = (new Config())
    ->add('field_2', 'Some description', State::Required, [
        new VInteger(),
        new VMin(10),
        new VMax(20),
    ])
;

```

And the last - some code for validation and map

```php

$result = (new Mapper($form, $config))->toObject($input, new ReflectionSetter());

```

If you have an input: `['field_2' => 15]` it's correct and `$input->field_2` will have a value of `15`. But for
input `['field_2' => 999]` `$result` variable will contain a list of errors (VMax check fails, because 999 > 20).

### Validators

Builtin validator are described in [validators.md](validators.md) file. You can create own validator which
should implement `Validator` interface.

### Setters

Setters are special objects which map validated input to your object. There are two default setters - **Direct** and 
**Reflection**-based. First setter just apply input to object via `$object->$property` without extended checks. Second
setter checks types of properties via reflection and supported interfaces if property is another object.

### Localization

Localization files are located in /etc/ folder. There is a class for quick validation run: OValidator::validateAndSet()
It creates localization object from default file *loc-en.php*. You can pass path to your own file if needed.

### Examples

You can find **more examples** at the /examples/ dir. There are a lot of different cases where that mapper and validator
can be used. Fell free to create issue for any questions or improvements.
