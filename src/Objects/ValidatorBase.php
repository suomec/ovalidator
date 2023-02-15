<?php

declare(strict_types=1);

namespace OValidator\Objects;

use OValidator\Interfaces\I18n;
use OValidator\Interfaces\Validator;

abstract class ValidatorBase implements Validator
{
    protected ?I18n $i18n = null;

    public function setI18n(I18n $i18n): void
    {
        $this->i18n = $i18n;
    }

    /**
     * Default localization
     * @param string $message Message with templates
     * @param array<string, string|int> $replaces Template values
     * @return string Replaced message
     */
    protected function _(string $message, array $replaces = []): string
    {
        if ($this->i18n !== null) {
            return $this->i18n->_($message, $replaces);
        }

        $string = $message;
        foreach ($replaces as $k => $v) {
            $r = sprintf('{%s}', $k);
            if (!str_contains($string, $r)) {
                throw new \Exception("Key {$r} not found for replacement");
            }

            if (is_int($v)) {
                $v = "{$v}";
            }

            $string = str_replace($r, $v, $string);
        }

        return $string;
    }
}
