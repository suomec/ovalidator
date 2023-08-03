## Builtin validators

Located at /src/Engines dir. Tests located at /tests/Engines dir.

### VArray

```php

new VArray([
    // here you can pass validators list for each array item
    new VMin(0),
], $keepOnlyUniqueValues, $keepOriginalKeys);

```

`VArray` checks that every array item pass all validators given as first parameter. If second parameter 
`$keepOnlyUniqueValues` is `true` then result value contains only unique elements. If `$keepOriginalKeys` 
is `false` then result array keys are reset (you get only values of original array).


### VArrayFromJson

```php

new VArrayFromJson();

```

`VArrayFromJson` creates array from JSON-string. You can pass `{"a": 1, "b": 2}` or `[1, 2]` as input string.

### VArrayFromString

```php

new VArrayFromString($separator, $excludeEmptyStrings);

```

`VArrayFromString` creates array from string. `$separator` is separator string. If input is `1|2|3|4` and `$separator`
is `|` then result array will be `['1', '2', '3', '4']`. `$excludeEmptyStrings` is true by default. For input `1|2|`
and `$excludeEmptyStrings` = `true` result array will be `['1', '2']`, but for `$excludeEmptyStrings` = `false`
result array will be `['1', '2', '']` (last item is empty string).

### VBool

```php

new VBool();

```

`VBool` matches input value to bool value. `on`, `yes`, `true` or `1` is php-TRUE value. See sources for other allowed values.

### VCallback

```php

new VCallback(function($v) {
    return $v . '-suffix';
});

```

`VCallback` applies user function to input value. For input `TEST` and that callback result value will be `TEST-suffix`.

### VDateTime

```php

new VDateTime($format, $asString);

```

`VDateTime` creates DateTimeImmutable object from input string with date-time and specific `$format`. If `$asString`
is false, then result value will be string, not DateTimeImmutable (rare cases).

### VEmail

```php

new VEmail();

```

`VEmail` runs php builtin `filter_var()` func with `FILTER_VALIDATE_EMAIL` filter.

### VEnum

```php

new VEnum(YourEnum::class, [YourEnum::Exclude1, YourEnum::Exclude2]);

```

First parameter is you enum-class. Second is optional - list of disallowed cases. `VEnum` creates enum-instance
from string input. If input string is `Value` and `YourEnum` has `Value` case result value will be `YourEnum::Value`.
For input string `Exclude1` validation error will be generated. Second parameter is optional.

### VFloat

```php

new VFloat();

```

`VFloat` creates float-value from input. Input could be string or float value.

### VImage

```php

new VImage($constraintsList, $allowedTypes);

```

`VImage` creates GD-resource from base64 encoded image string given as input. `$constraintsList` is list of classes
which implement `ConstraintInterface`. Builtin constraints are `VImageConstraintSizes` - checks that image
has min/max height or width or filesize, and `VImageConstraintDisallowAnimatedGif` disables animated GIFs.
`$allowedTypes` are list of GD-constants IMAGETYPE_JPEG, IMAGETYPE_GIF ...

### VInSet

```php

new VInSet($allowed, $shouldReturnIndexInsteadOfValue);

```

`VInSet` checks that value belongs to set (strings, integers ot floats). `$allowed` is a list of allowed values such
as `[1, 2, 3]` or `['one', 'two']`. If input is `2` or `one` for such `$allowed` validator will return input as is.
If `$shouldReturnIndexInsteadOfValue` is true `VInSet` will return item index in `$allowed` array (rare use case).


### VInstanceOf

```php

new VInstanceOf(YourClassOrInterface::class);

```

`VInstanceOf` checks that value has correct type.

### VInteger

```php

new VInteger();

```

`VInteger` checks that input is integer or integer-string.

### VMax and VMin

```php

new VMax(10);
new VMin(20);

```

This validator checks that input value has min or max value. For array - it's size. For string - it's length (UTF8).
For int/float - it's value.

### VObject

```php

new VObject(YourClass::class);

class YourClass implements CanBeValidated
{
    public int $value;
    
    public function getValidationConfig(): Config
    {
        return Config()
            ->add('value', 'Description', State::Required, [new VInteger()])
        ;
    }
}

```

`VObject` transforms input array (could be loaded from JSON) to your object with defined class. If you have a class
`YourClass` and input `["value": 10]` `VObject` will return instance of `YourClass` with property `value` = 10.
`YourClass` should implement `CanBeValidated` and return self validation config;

### VSkip

```php

new VSkip();

```

`VSkip` - no checks.

### VString

```php

new VString($shouldTrimInput);

```

`VString` checks that input is string ot converts value to string (for Stringable interfaces). If `$shouldTrimInput`
is `true` then input ` VALUE ` will be converted to `VALUE` without whitespace.
