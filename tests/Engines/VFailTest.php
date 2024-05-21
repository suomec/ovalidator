<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VFail;
use OValidator\Exceptions\EngineException;
use OValidator\Objects\LocPhpFile;
use PHPUnit\Framework\TestCase;

class VFailTest extends TestCase
{
    public function testFailEngineSuccess(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('test message');

        $this->get('test message')->check('any value');
    }

    private function get(string $message): VFail
    {
        $l = new LocPhpFile(__DIR__ . '/../../etc/loc-en.php');

        $v = new VFail($message);
        $v->setLocalization($l);

        return $v;
    }
}
