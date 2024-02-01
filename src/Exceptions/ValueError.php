<?php

namespace Enderive\Enum\Exceptions;

use Error;
use ReflectionClass;

class ValueError extends Error
{
    /**
     * @param class-string $classname
     * @param int|string $value
     *
     * @return self
     */
    public static function forInvalidCase(string $classname, $value)
    {
        $classname = (new ReflectionClass($classname))->getShortName();
        $message = sprintf('"%s" is not a valid backing value for enum "%s"', $value, $classname);

        return new ValueError($message);
    }

    /**
     * @param mixed $value
     * @return self
     */
    public static function forInvalidDataType($value)
    {
        $message = sprintf('Value must be either integer or string, "%s" given', gettype($value));

        return new ValueError($message);
    }
}
