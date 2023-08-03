<?php

return [
    'VArray' => [
        'NOT_ARRAY' => 'should be array',
    ],
    'VArrayFromJson' => [
        'DEPTH_TOO_SMALL' => 'depth should be more than 0',
        'NOT_STRING' => 'value should be string',
        'NOT_ARRAY' => 'value should be JSON-array',
    ],
    'VArrayFromString' => [
        'NOT_STRING' => 'value should be string',
    ],
    'VBool' => [
        'BAD_FORMAT' => 'boolean format not parsed',
    ],
    'VDateTime' => [
        'CANT_PARSE' => "can't parse date for format: {format}",
        'BAD_TYPE' => 'should be string or DateTimeImmutable',
    ],
    'VEmail' => [
        'NOT_STRING' => 'should be string',
        'NOT_EMAIL' => 'not an email',
    ],
    'VEnum' => [
        'CASE_NOT_FOUND' => 'case not found in: {names}',
    ],
    'VFloat' => [
        'NOT_FLOAT' => 'is not float',
    ],
    'VImage' => [
        'NOT_STRING' => 'value not string',
        'BAD_PREFIX' => 'error stripping image prefix',
        'BAD_BASE64' => "can't decode image contents from base64",
        'NOT_IMAGE' => 'data is not an image',
        'BAD_SIZES' => "can't get image sizes",
        'CONSTRAINT_ERROR' => "image constraint '{name}' error: {message}",
        'TYPE_NOT_ALLOWED' => 'image type is not allowed (only {types} supported)',
    ],
    'VInSet' => [
        'TYPE_NOT_ALLOWED' => 'value type should be: string, int, float',
        'VALUE_NOT_ALLOWED' => 'value is not allowed (not in set)',
        'VALUE_BY_INDEX_NOT_FOUND' => 'value by index not found',
    ],
    'VInstanceOf' => [
        'NOT_INSTANCE' => 'should be instance of {type}',
    ],
    'VInteger' => [
        'NOT_NUMERIC' => 'should be numeric string',
        'CANT_BE_FLOAT' => 'floats not allowed',
        'LOOKS_LIKE_FLOAT' => 'seems to be float',
    ],
    'VMax' => [
        'NUM_ERROR' => 'number must be less than {max}',
        'STRING_ERROR' => 'string should contain at most {max} characters but contains {size}',
        'ARRAY_ERROR' => 'array should contain at most {max} items but contains {size}',
        'TYPE_ERROR' => 'checks only numbers, strings and arrays, got: {type}',
    ],
    'VMin' => [
        'NUM_ERROR' => 'number must be greater than {min}',
        'STRING_ERROR' => 'string should contain at least {min} characters but contains {size}',
        'ARRAY_ERROR' => 'array should contain at least {min} items but contains {size}',
        'TYPE_ERROR' => 'checks only numbers, strings and arrays, got: {type}',
    ],
    'VObject' => [
        'SHOULD_BE_ARRAY' => 'value should be array',
        'VALIDATION_ERROR' => 'object validation error[{error}]',
    ],
    'VString' => [
        'SHOULD_BE_STRING' => 'should be string',
    ],
];
