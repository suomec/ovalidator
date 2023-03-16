<?php

declare(strict_types=1);

namespace OValidator\Engines\VImage;

/**
 * Base class for constraints
 */
abstract class ConstraintBase implements ConstraintInterface
{
    protected ?string $lastError = null;
    /** @var array<string, string|int> */
    protected array $replaces = [];

    /**
     * @param string $error Message: "test error {replace1}"
     * @param array<string, string|int> $replaces Error message replaces [replace1 => value1]
     * @return bool Always false - for return from check() method
     */
    protected function setLastError(string $error, array $replaces = []): bool
    {
        $this->lastError = $error;
        $this->replaces = $replaces;

        return false;
    }

    /**
     * @return string|null
     */
    public function getLastErrorMessage(): ?string
    {
        return $this->lastError;
    }

    public function getLastErrorReplaces(): array
    {
        return $this->replaces;
    }
}
