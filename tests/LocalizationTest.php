<?php

declare(strict_types=1);

namespace OValidator\Tests;

use PHPUnit\Framework\TestCase;

class LocalizationTest extends TestCase
{
    public function testLocalizationFilesAtEtcHasSameKeys(): void
    {
        $locFiles = glob(__DIR__ . '/../etc/loc-*.php');
        if (is_bool($locFiles)) {
            $this->fail("can't locate loc-files");
        }

        $locStructs = [];
        foreach ($locFiles as $file) {
            $tmp = require $file;

            $struct = [];
            foreach ($tmp as $k => $v) {
                $struct[$k] = array_keys($v);
            }

            $locStructs[] = $struct;
        }

        for ($i = 0, $max = count($locStructs); $i < ($max - 1); $i++) {
            $this->assertEquals($locStructs[$i], $locStructs[$i + 1]);
        }
    }
}
