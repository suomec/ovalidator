<?php

declare(strict_types=1);

namespace OValidator\Tests\Samples;

class SObject1
{
    public int $pIntReq;
    public ?int $pIntOpt1;
    public ?int $pIntOpt2;
    public ?int $pIntOpt3;

    public string $pStrReq;
    public ?string $pStrOpt1;
    public ?string $pStrOpt2;
    public ?string $pStrOpt3;

    public float $pFloatReq;
    public ?float $pFloatOpt;

    public bool $pBoolReq;
    public ?bool $pBoolOpt;

    // @phpstan-ignore-next-line
    public $pNoType;

    public \stdClass $pStdClass;
    public SObject2 $pObjProp;
    public SInterface $pObjPropInt;
}
