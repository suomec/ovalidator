<?php

declare(strict_types=1);

namespace OValidator\Interfaces;

/**
 * What validator should do
 */
interface Validator
{
    /**
     * Main check and transformation of input
     * @param mixed $value Input data
     * @return mixed Result (input or modified input)
     */
    public function check(mixed $value): mixed;

    /**
     * @return string Validator description, based on its config and type
     */
    public function getDescription(): string;

    /**
     * Set localization object before check()
     * @param Localization $localization Instance
     * @return void
     */
    public function setLocalization(Localization $localization): void;
}
