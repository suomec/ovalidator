<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VRegExpMatch;
use OValidator\Exceptions\EngineException;
use OValidator\Objects\LocPhpFile;
use PHPUnit\Framework\TestCase;

class VRegExpMatchTest extends TestCase
{
    public function testRegExpMatchEngineFailedIfInputNotString(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('should be string');

        ($this->get('/\d\d\d/s'))->check(123);
    }

    public function testRegExpMatchEngineSuccessIfInputNotMatched(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('input not matched to regexp: PUBLIC');

        ($this->get('/\d\d/', 'PUBLIC'))->check('test');
    }

    public function testRegExpMatchEngineFailsIfRegExpInvalid(): void
    {
        $this->expectException(EngineException::class);
        $this->expectExceptionMessage('regular expression is invalid: Backtrack limit exhausted');

        // https://www.php.net/manual/en/function.preg-last-error.php
        ($this->get('/(?:\D+|<\d+>)*[!?]/', 'PUBLIC'))->check('foobar foobar foobar');
    }

    public function testRegExpMatchEngineSuccessIfInputCorrect(): void
    {
        $value = ($this->get('/^\d\d\d$/', 'PUBLIC'))->check('123');
        $this->assertEquals('123', $value);
    }

    private function get(string $pattern, ?string $patternPublic = null): VRegExpMatch
    {
        $l = new LocPhpFile(__DIR__ . '/../../etc/loc-en.php');

        $v = new VRegExpMatch($pattern, $patternPublic);
        $v->setLocalization($l);

        return $v;
    }
}
