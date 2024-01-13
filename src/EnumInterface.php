<?php

namespace Enderive\Enum;

use Enderive\Enum\Exceptions\ValueError;

interface EnumInterface
{
    /**
     * Create enum from value.
     *
     * @param string|int $value
     * @return static
     *
     * @throws ValueError
     *
     * @psalm-api
     */
    public static function from($value);

    /**
     * Create enum instance without throwing an exception on invalid value.
     *
     * @param string|int $value
     * @return static|null
     *
     * @psalm-api
     */
    public static function tryFrom($value);

    /**
     * Get the list of all the possible cases for current enum class.
     *
     * @return array<static>
     *
     * @psalm-api
     */
    public static function cases(): array;
}
