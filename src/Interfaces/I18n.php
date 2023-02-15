<?php

declare(strict_types=1);

namespace OValidator\Interfaces;

/**
 * Internationalization
 */
interface I18n
{
    /**
     * Localize string
     * @param string $message Original message in English: "hello {somebody}"
     * @param array<string, string|int> $replaces Replaces ['somebody' => 'world']
     * @return string Localized string
     */
    public function _(string $message, array $replaces): string;
}
