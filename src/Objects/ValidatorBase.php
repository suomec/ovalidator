<?php

declare(strict_types=1);

namespace OValidator\Objects;

use OValidator\Interfaces\Localization;
use OValidator\Interfaces\Validator;

abstract class ValidatorBase implements Validator
{
    protected ?Localization $localization = null;

    public function setLocalization(Localization $localization): void
    {
        $this->localization = $localization;
    }

    /**
     * @param string $code Error message key (default location is /etc/loc-*.php)
     * @param array<string, string|int> $replaces Replaces for localization
     * @return string Localized message
     */
    protected function _(string $code, array $replaces = []): string
    {
        if ($this->localization === null) {
            return $code;
        }

        $class = basename(str_replace('\\', '/', get_class($this)));

        return $this->localization->_($class, $code, $replaces);
    }
}
