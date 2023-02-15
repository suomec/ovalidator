<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\ValidatorException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be image
 */
class VImage extends ValidatorBase
{
    public const IMAGE_TYPE_FROM_STRING = 'IMAGE_TYPE_FROM_STRING';

    private string $type;
    private ?int $maxWidth;
    private ?int $maxHeight;
    private ?int $minWidth;
    private ?int $minHeight;
    private ?int $maxSizeKb;
    private ?bool $disableAnimatedGif;

    public function __construct(
        string $type,
        ?int $maxWidth,
        ?int $maxHeight,
        ?int $minWidth,
        ?int $minHeight,
        ?int $maxSizeKb,
        ?bool $disableAnimatedGif,
    ) {
        $this->type = $type;

        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
        $this->minWidth = $minWidth;
        $this->minHeight = $minHeight;
        $this->maxSizeKb = $maxSizeKb;

        $this->disableAnimatedGif = $disableAnimatedGif;
    }

    public function check(mixed $value): mixed
    {
        switch ($this->type) {
            case self::IMAGE_TYPE_FROM_STRING:
                if (!is_string($value) || !$value) {
                    throw new ValidatorException($this->_('image not passed or not string'));
                }

                if (str_starts_with($value, 'data:image/png;base64,')) {
                    $content = str_replace('data:image/png;base64,', '', $value);
                } elseif (str_starts_with($value, 'data:image/jpeg;base64,')) {
                    $content = str_replace('data:image/jpeg;base64,', '', $value);
                } elseif (str_starts_with($value, 'data:image/gif;base64,')) {
                    $content = str_replace('data:image/gif;base64,', '', $value);
                } else {
                    $content = $value;
                }

                $content = base64_decode($content);
                if (!is_string($content)) {
                    throw new ValidatorException($this->_('cannot base64-decode image contents'));
                }

                break;
            default:
                throw new \Exception('unknown image type');
        }

        if (strlen($content) > $this->maxSizeKb*1024) {
            throw new ValidatorException($this->_('image size more than limit (Kb): ' . $this->maxSizeKb));
        }

        try {
            $image = @imagecreatefromstring($content);
        } catch (\Exception $e) {
            throw new ValidatorException($this->_('data is not an image: ' . $e->getMessage()));
        }

        if (!$image) {
            throw new ValidatorException($this->_('data is not an image'));
        }

        $imageSize = getimagesizefromstring($content);
        if ($imageSize === false) {
            throw new ValidatorException($this->_('cannot get image sizes'));
        }

        if ($this->maxWidth !== null && $imageSize[0] > $this->maxWidth) {
            throw new ValidatorException($this->_('image width more than max: ' . $this->maxWidth));
        }
        if ($this->minWidth !== null && $imageSize[0] < $this->minWidth) {
            throw new ValidatorException($this->_('image width less than min: ' . $this->minWidth));
        }

        if ($this->maxHeight !== null && $imageSize[1] > $this->maxHeight) {
            throw new ValidatorException($this->_('image height more than max: ' . $this->maxHeight));
        }
        if ($this->minHeight !== null && $imageSize[1] < $this->minHeight) {
            throw new ValidatorException($this->_('image height less than min: ' . $this->minHeight));
        }

        if (!in_array($imageSize[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF], true)) {
            $iType = image_type_to_mime_type($imageSize[2]);

            throw new ValidatorException($this->_('image type is not JPEG, GIF или PNG, but ' . $iType));
        }

        if ($this->disableAnimatedGif === true && $imageSize[2] === IMAGETYPE_GIF && $this->isGifAnimated($content)) {
            throw new ValidatorException($this->_('animated gifs are not allowed'));
        }

        return new VImageDescriptor(
            $content,
            $imageSize[2],
            (int)$imageSize[0],
            (int)$imageSize[1],
        );
    }

    public function getDescription(): string
    {
        switch ($this->type) {
            case self::IMAGE_TYPE_FROM_STRING:
                $type = 'base64 encoded string with image contents';
                break;
            default:
                throw new \Exception('unknown image type');
        }

        return 'Should be image: ' . $type;
    }

    /**
     * Is GIF animated
     * https://www.php.net/manual/en/function.imagecreatefromgif.php#59787
     * @param string $contents Image contents
     * @return bool Yes or no
     */
    private function isGifAnimated(string $contents): bool
    {
        $strLoc = 0;
        $count = 0;
        while ($count < 2) {
            $where1 = strpos($contents, "\x00\x21\xF9\x04", $strLoc);
            if ($where1 === false) {
                break;
            }

            $strLoc = $where1 + 1;
            $where2 = strpos($contents, "\x00\x2C", $strLoc);
            if ($where2 === false) {
                break;
            }

            if (($where1 + 8) === $where2) {
                $count++;
            }

            $strLoc = $where2 + 1;
        }

        return $count > 1;
    }
}
