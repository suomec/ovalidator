<?php

declare(strict_types=1);

namespace OValidator\Engines;

/**
 * Container for image data
 */
class VImageDescriptor
{
    private string $content;
    private int $type;
    private int $width;
    private int $height;

    public function __construct(string $content, int $type, int $width, int $height)
    {
        $this->content = $content;
        $this->type = $type;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return string Image contents
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return int Image type (GD IMAGETYPE_*** constant)
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return int Image height
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return int Image width
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param string $content Setting new image content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
