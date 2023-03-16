<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VImage;
use OValidator\Engines\VImage\ConstraintInterface;
use OValidator\Engines\VImage\Descriptor;
use OValidator\Exceptions\EngineException;
use PHPUnit\Framework\TestCase;

class VImageTest extends TestCase
{
    /**
     * @dataProvider providerImage
     * @param ?ConstraintInterface[] $constraints
     */
    public function testImageEngineSuccess(mixed $input, ?array $constraints): void
    {
        $engine = new VImage($constraints);

        $this->assertInstanceOf(Descriptor::class, $engine->check($input));
    }

    public function testImageEngineFailedIfConstraintHasIncorrectType(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('every VImage constraint should implement ConstraintInterface');

        //@phpstan-ignore-next-line
        new VImage([new \stdClass()]);
    }

    public function testImageEngineFailedIfAllowedImagesTypesListIsEMpty(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('you should pass at least one allowed image type');

        new VImage(null, []);
    }

    public function testImageEngineFailedIfInputNotString(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('value not string');

        (new VImage())->check(123);
    }

    public function testImageEngineFailedIfInputNotBase64Encoded(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage("can't decode image contents from base64");

        (new VImage())->check('!@#$%^&*(');
    }

    public function testImageEngineFailedIfInputNotImage(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('data is not an image');

        (new VImage())->check('aaa');
    }

    public function testImageEngineFailedIfConstraintFailedWithMessageWithReplacement(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage("image constraint 'NAME' error: message R1");

        $constraintFail = $this->createMock(ConstraintInterface::class);
        $constraintFail->method('check')->willReturn(false);
        $constraintFail->method('getVisibleName')->willReturn('NAME');
        $constraintFail->method('getLastErrorMessage')->willReturn('message {replacement1}');
        $constraintFail->method('getLastErrorReplaces')->willReturn(['replacement1' => 'R1']);

        (new VImage([$constraintFail]))->check($this->getImage(0));
    }

    public function testImageEngineFailedIfConstraintFailedWithoutMessage(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage("image constraint 'NAME' error: no error");

        $constraintFail = $this->createMock(ConstraintInterface::class);
        $constraintFail->method('check')->willReturn(false);
        $constraintFail->method('getVisibleName')->willReturn('NAME');
        $constraintFail->method('getLastErrorMessage')->willReturn(null);

        (new VImage([$constraintFail]))->check($this->getImage(0));
    }

    public function testImageEngineFailedIfTypeDisallowed(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('image type is not allowed (only image/gif supported)');

        $constraintOk = $this->createMock(ConstraintInterface::class);
        $constraintOk->method('check')->willReturn(true);

        (new VImage([$constraintOk], [IMAGETYPE_GIF]))->check($this->getImage(0));
    }

    public function testImageConstraintDisableAnimatedGifSuccessForNonGif(): void
    {
        $constraint = new VImage\VImageConstraintDisallowAnimatedGif();

        $result = $constraint->check([2 => IMAGETYPE_PNG], '', $this->getResource());
        $this->assertTrue($result);
    }

    public function testImageConstraintDisableAnimatedGifFailForAnimatedGif(): void
    {
        $constraint = new VImage\VImageConstraintDisallowAnimatedGif();

        $result = $constraint->check([2 => IMAGETYPE_GIF], $this->getImage(2, true), $this->getResource());
        $this->assertFalse($result);
    }

    public function testImageConstraintSizesFailIfFileSizeTooBig(): void
    {
        $constraint = new VImage\VImageConstraintSizes(null, null, null, null, 1);
        $result = $constraint->check([], str_repeat('a', 8000), $this->getResource());
        $this->assertFalse($result);
    }

    public function testImageConstraintSizesFailIfWidthTooBig(): void
    {
        $constraint = new VImage\VImageConstraintSizes(10, null, null, null, null);
        $result = $constraint->check([0 => 20], 'aaa', $this->getResource());
        $this->assertFalse($result);
    }

    public function testImageConstraintSizesFailIfWidthTooSmall(): void
    {
        $constraint = new VImage\VImageConstraintSizes(null, null, 10, null, null);
        $result = $constraint->check([0 => 5], 'aaa', $this->getResource());
        $this->assertFalse($result);
    }

    public function testImageConstraintSizesFailIfHeightTooBig(): void
    {
        $constraint = new VImage\VImageConstraintSizes(null, 10, null, null, null);
        $result = $constraint->check([1 => 20], 'aaa', $this->getResource());
        $this->assertFalse($result);
    }

    public function testImageConstraintSizesFailIfHeightTooSmall(): void
    {
        $constraint = new VImage\VImageConstraintSizes(null, null, null, 10, null);
        $result = $constraint->check([1 => 3], 'aaa', $this->getResource());
        $this->assertFalse($result);
    }

    /**
     * @return array<mixed>
     */
    private function providerImage(): array
    {
        $constraintOk = $this->createMock(ConstraintInterface::class);
        $constraintOk->method('check')->willReturn(true);

        return [
            [$this->getImage(0), null],
            [$this->getImage(0), []],
            [$this->getImage(0), [$constraintOk]],
            [$this->getImage(0), [$constraintOk, $constraintOk]],
            [$this->getImage(1), []],
        ];
    }

    private function getResource(): \GdImage
    {
        $i = imagecreate(1, 1);
        if (is_bool($i)) {
            $this->fail("can't create resource");
        }

        return $i;
    }

    private function getImage(int $index, bool $decoded = false): string
    {
        $images = [
            'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQG' .
                'AhKmMIQAAAABJRU5ErkJggg==',
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mM01tOrBwACOwEQjRjzWgAAAABJRU5ErkJg' .
                'gg==',
            'R0lGODlhAQABAPAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQBFAAAACwAAAAAAQABAAACAkQBACH5BAEUAAAAL' .
                'AAAAAABAAEAAAICRAEAOw==', // animated
        ];

        $result = $images[$index];
        if ($decoded === false) {
            return $result;
        }

        $d = base64_decode($result);
        if (!is_string($d)) {
            $this->fail("can't decode image");
        }

        return $d;
    }
}
