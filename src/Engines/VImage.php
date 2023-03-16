<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Engines\VImage\ConstraintInterface;
use OValidator\Engines\VImage\Descriptor;
use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be base64-encoded image
 * Requires GD extension
 */
class VImage extends ValidatorBase
{
    /** @var ConstraintInterface[] */
    private array $constraints;
    /** @var int[] */
    private array $allowedTypes;

    /**
     * @param ?ConstraintInterface[] $constraints
     * @param int[] $allowedTypes
     * @throws \Exception
     */
    public function __construct(
        ?array $constraints = null,
        array $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_PNG],
    ) {
        if (!function_exists('imagecreatefromstring')) {
            throw new \Exception('gd extension is required for VImage validator');
        }

        $this->constraints = [];

        if ($constraints !== null && count($constraints) > 0) {
            foreach ($constraints as $constraint) {
                if (!($constraint instanceof ConstraintInterface)) {
                    throw new \Exception($this->_('every VImage constraint should implement ConstraintInterface'));
                }
            }

            $this->constraints = $constraints;
        }

        if (count($allowedTypes) === 0) {
            throw new \Exception('you should pass at least one allowed image type');
        }

        $this->allowedTypes = $allowedTypes;
    }

    public function check(mixed $value): mixed
    {
        if (!is_string($value)) {
            throw new EngineException($this->_('value not string'));
        }

        $content = preg_replace('|(data:image/[a-z]+;base64,)(.+)|i', '${2}', $value);
        if (!is_string($content)) {
            throw new EngineException($this->_('error stripping image prefix'));
        }

        $content = base64_decode($content);
        if (!is_string($content) || $content === '') {
            throw new EngineException($this->_("can't decode image contents from base64"));
        }

        try {
            $image = @imagecreatefromstring($content);
        } catch (\Exception $e) {
            throw new EngineException($this->_('data is not an image: ' . $e->getMessage()));
        }

        if (!$image) {
            throw new EngineException($this->_('data is not an image'));
        }

        $imageSizes = getimagesizefromstring($content);
        if ($imageSizes === false) {
            throw new EngineException($this->_("can't get image sizes"));
        }

        foreach ($this->constraints as $constraint) {
            $result = $constraint->check($imageSizes, $content, $image);
            if ($result === false) {
                $message = $constraint->getLastErrorMessage();
                if ($message !== null) {
                    $error = $this->_($message, $constraint->getLastErrorReplaces());
                } else {
                    $error = 'no error';
                }

                throw new EngineException($this->_("image constraint '{name}' error: {message}", [
                    'name'    => $constraint->getVisibleName(),
                    'message' => $error,
                ]));
            }
        }

        if (!in_array($imageSizes[2], $this->allowedTypes, true)) {
            $allowed = [];
            foreach ($this->allowedTypes as $type) {
                $allowed[] = image_type_to_mime_type($type);
            }

            throw new EngineException($this->_('image type is not allowed (only {types} supported)', [
                'types' => implode(', ', $allowed),
            ]));
        }

        return new Descriptor(
            $content,
            $imageSizes[2],
            (int)$imageSizes[0],
            (int)$imageSizes[1],
            $image,
        );
    }

    public function getDescription(): string
    {
        return 'base64-encoded string with image contents';
    }
}
