<?php

declare(strict_types=1);

namespace OValidator\Interfaces;

interface Localization
{
    /**
     * Main localization method
     * @param string $validatorClass Class name of validator
     * @param string $messageCode Code of error from validator
     * @param array<string, string|int> $replaces Replaces for {key} for value of error code
     * @return string String of chosen language
     */
    public function _(string $validatorClass, string $messageCode, array $replaces = []): string;
}
