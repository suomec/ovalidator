<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\ValidatorException;
use OValidator\Objects\ValidatorBase;

/**
 * Validator which makes array from string-field
 */
class VMakeArray extends ValidatorBase
{
    public const TYPE_BY_SEPARATOR = 1;
    public const TYPE_FROM_JSON = 2;

    private int $type;
    private mixed $config;

    public function __construct(int $type, mixed $config = null)
    {
        switch ($type) {
            case self::TYPE_BY_SEPARATOR:
                if (!is_string($config)) {
                    throw new \Exception('VMakeArray second parameter should be string');
                }
                $this->config = $config;
                break;
            case self::TYPE_FROM_JSON:
                $this->config = null;
                break;
            default:
                throw new \Exception('unknown VMakeArray type when creating validator');
        }

        $this->type = $type;
    }

    public function check(mixed $value): mixed
    {
        if (!is_string($value)) {
            throw new ValidatorException($this->_('input should be string'));
        }

        switch ($this->type) {
            case self::TYPE_BY_SEPARATOR:
                /** @var string $sep */
                $sep = $this->config;

                if ($sep === '' || !str_contains($value, $sep)) {
                    return [$value];
                }

                return explode($sep, $value);

            case self::TYPE_FROM_JSON:
                $decoded = json_decode($value, true);
                if (!is_array($decoded)) {
                    throw new ValidatorException($this->_('should be json-array'));
                }

                return $decoded;
        }

        throw new \Exception('unknown VMakeArray type');
    }

    public function getDescription(): string
    {
        switch ($this->type) {
            case self::TYPE_BY_SEPARATOR:
                /** @var string $sep */
                $sep = $this->config;

                return 'Array from string, separated by: ' . $sep;

            case self::TYPE_FROM_JSON:
                return 'Array from JSON-encoded string';
        }

        throw new \Exception('unknown VMakeArray type');
    }
}
