<?php

declare(strict_types=1);

namespace OValidator\Engines\VImage;

/**
 * Disallow animated GIF constraint
 */
class VImageConstraintDisallowAnimatedGif extends ConstraintBase
{
    public function check(array $imageSizes, string $content, \GdImage $resource): bool
    {
        if ($imageSizes[2] !== IMAGETYPE_GIF) {
            return true;
        }

        if ($this->isGifAnimated($content)) {
            return $this->setLastError('animated GIFs disallowed');
        }

        return true;
    }

    public function getVisibleName(): string
    {
        return 'No Animated GIFs';
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
