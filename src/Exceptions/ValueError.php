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
    public static function for(string $classname, $value)
    {
        $classname = (new ReflectionClass($classname))->getShortName();
        $message = sprintf('"%s" is not a valid backing value for enum "%s"', $value, $classname);

        return new ValueError($message);
    }
}
