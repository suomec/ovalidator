<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should match regular expression
 */
class VRegExpMatch extends ValidatorBase
{
    private string $pattern;
    private ?string $patternPublic;

    /**
     * @param string $pattern Full regexp pattern, for example: /^\d\d-\d\d$/ui
     * @param ?string $patternPublic Public representation of regexp for user errors (if null source will be exposed)
     */
    public function __construct(string $pattern, ?string $patternPublic = null)
    {
        $this->pattern = $pattern;
        $this->patternPublic = $patternPublic;
    }

    public function check(mixed $value): mixed
    {
        if (!is_string($value)) {
            throw new EngineException($this->_('SHOULD_BE_STRING'));
        }

        $result = preg_match($this->pattern, $value);

        if (preg_last_error() !== PREG_NO_ERROR) {
            throw new EngineException($this->_('REGEXP_ERROR', ['message' => preg_last_error_msg()]));
        }

        if (!$result) {
            throw new EngineException($this->_('NOT_MATCHED', [
                'regexp' => $this->patternPublic ?? $this->pattern,
            ]));
        }

        return $value;
    }

    public function getDescription(): string
    {
        return 'Should match regular expression: ' . ($this->patternPublic ?? $this->pattern);
    }
}
