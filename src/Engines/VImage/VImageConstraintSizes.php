<?php

declare(strict_types=1);

namespace OValidator\Engines\VImage;

/**
 * Image sizes constraints
 */
class VImageConstraintSizes extends ConstraintBase
{
    private ?int $maxWidth;
    private ?int $maxHeight;
    private ?int $minWidth;
    private ?int $minHeight;
    private ?int $maxSizeKb;

    public function __construct(
        ?int $maxWidth,
        ?int $maxHeight,
        ?int $minWidth,
        ?int $minHeight,
        ?int $maxSizeKb,
    ) {
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
        $this->minWidth = $minWidth;
        $this->minHeight = $minHeight;
        $this->maxSizeKb = $maxSizeKb;
    }

    public function check(array $imageSizes, string $content, \GdImage $resource): bool
    {
        if ($this->maxSizeKb !== null && strlen($content) > $this->maxSizeKb*1024) {
            return $this->setLastError('image size more than limit ({limit}Kb)', ['limit' => $this->maxSizeKb]);
        }

        if ($this->maxWidth !== null && $imageSizes[0] > $this->maxWidth) {
            return $this->setLastError('image width more than limit ({limit}px)', ['limit' => $this->maxWidth]);
        }
        if ($this->minWidth !== null && $imageSizes[0] < $this->minWidth) {
            return $this->setLastError('image width less than min limit ({limit}px)', ['limit' => $this->minWidth]);
        }

        if ($this->maxHeight !== null && $imageSizes[1] > $this->maxHeight) {
            return $this->setLastError('image height more than limit ({limit}px)', ['limit' => $this->maxHeight]);
        }
        if ($this->minHeight !== null && $imageSizes[1] < $this->minHeight) {
            return $this->setLastError('image height less than min limit ({limit}px)', ['limit' => $this->minHeight]);
        }

        return true;
    }

    public function getVisibleName(): string
    {
        return 'Image Sizes';
    }
}
