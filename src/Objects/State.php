<?php

declare(strict_types=1);

namespace OValidator\Objects;

/**
 * Input field is required or optional
 */
enum State: int
{
    case Required = 1;
    case Optional = 2;
}
