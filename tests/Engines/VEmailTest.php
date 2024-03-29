<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VEmail;
use OValidator\Exceptions\EngineException;
use OValidator\Objects\LocPhpFile;
use PHPUnit\Framework\TestCase;

class VEmailTest extends TestCase
{
    /**
     * @dataProvider providerEmail
     */
    public function testEmailEngineSuccess(mixed $input, string $result): void
    {
        $engine = new VEmail();

        $this->assertEquals($result, $engine->check($input));
    }

    public function testEmailEngineFailedOnBadInputType(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('should be string');

        ($this->get())->check(123);
    }

    public function testEmailEngineFailedOnIncorrectEmail(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('not an email');

        ($this->get())->check('12312312___');
    }

    /**
     * @return array<mixed>
     */
    public function providerEmail(): array
    {
        return [
            [' a@a.com ', 'a@a.com'],
        ];
    }

    private function get(): VEmail
    {
        $l = new LocPhpFile(__DIR__ . '/../../etc/loc-en.php');

        $v = new VEmail();
        $v->setLocalization($l);

        return $v;
    }
}
