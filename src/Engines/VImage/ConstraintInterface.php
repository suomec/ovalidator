<?php

declare(strict_types=1);

namespace OValidator\Engines\VImage;

/**
 * What image constraint should do
 */
interface ConstraintInterface
{
    /**
     * Should check image for internal rules
     * @param array<mixed> $imageSizes Result of getimagesizefromstring() call
     * @param string $content Image data
     * @param \GdImage $resource Image as resource
     * @return bool Should return true if check OK, false on fail
     */
    public function check(array $imageSizes, string $content, \GdImage $resource): bool;

    /**
     * @return string Name to show
     */
    public function getVisibleName(): string;

    /**
     * @return string|null Error message for last check() call, empty if none
     */
    public function getLastErrorMessage(): ?string;

    /**
     * @return array<string, string|int> Get replaces for last error message
     */
    public function getLastErrorReplaces(): array;
}
