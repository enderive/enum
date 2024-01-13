<?php

namespace Enderive\Enum\Tests\Fixtures;

use Enderive\Enum\Enum;

class IntegerBackedEnum extends Enum
{
    private const ADMIN = 1;
    private const MEMBER = 2;
    private const GUEST = 3;
}