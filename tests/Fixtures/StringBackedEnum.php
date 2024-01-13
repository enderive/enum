<?php

namespace Enderive\Enum\Tests\Fixtures;

use Enderive\Enum\Enum;

/**
 * @psalm-api
 */
class StringBackedEnum extends Enum
{
    private const ADMIN = 'admin';
    private const MEMBER = 'member';
    private const GUEST = 'guest';
}