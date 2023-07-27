<?php

declare(strict_types=1);

namespace OValidator\Objects;

use OValidator\Interfaces\Localization;

/**
 * Localization from php-array from file
 */
class LocPhpFile implements Localization
{
    /** @var array<string, array<string, string>> */
    private array $config;
    private string $basename;

    public function __construct(string $constantsFilePath)
    {
        if (!file_exists($constantsFilePath)) {
            throw new \Exception("localization config file doesn't exists");
        }

        $this->config = require $constantsFilePath;
        $this->basename = basename($constantsFilePath);
    }

    public function _(string $validatorClass, string $messageCode, array $replaces = []): string
    {
        if (!array_key_exists($validatorClass, $this->config)) {
            throw new \Exception("localization key {$validatorClass} not found in config: {$this->basename}");
        }

        $values = $this->config[$validatorClass];
        if (!array_key_exists($messageCode, $values)) {
            throw new \Exception("message code '{$messageCode}' not found in config: {$this->basename}");
        }

        $value = $values[$messageCode];
        foreach ($replaces as $k => $v) {
            if (is_int($v)) {
                $v = (string)$v;
            }

            $value = str_replace(sprintf('{%s}', $k), $v, $value);
        }

        return $value;
    }
}
