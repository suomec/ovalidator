<?php

declare(strict_types=1);

namespace OValidator\Tests\Engines;

use OValidator\Engines\VBool;
use OValidator\Interfaces\CanBeValidated;
use OValidator\Interfaces\Config;
use OValidator\Objects\State;

class VObjectClassOk implements CanBeValidated
{
    public bool $a;

    public function getValidationConfig(): Config
    {
        return (new \OValidator\Config(false))
            ->add('a', '', State::Required, [new VBool()]);
    }
}
