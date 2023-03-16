<?php

declare(strict_types=1);

namespace OValidator\Engines\VImage;

/**
 * Container for image data
 */
final class Descriptor
{
    private string $content;
    private int $type;
    private int $width;
    private int $height;
    private \GdImage $resource;

    public function __construct(string $content, int $type, int $width, int $height, \GdImage $resource)
    {
        $this->content = $content;
        $this->type = $type;
        $this->width = $width;
        $this->height = $height;
        $this->resource = $resource;
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

    /**
     * @return \GdImage
     */
    public function getResource(): \GdImage
    {
        return $this->resource;
    }
}
