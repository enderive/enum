<?php

namespace Enderive\Enum\Exceptions;

use ReflectionClass;
use RuntimeException;

class InvalidEnumException extends RuntimeException
{
    /**
     * @param class-string $classname
     * @return self
     */
    public static function for(string $classname): self
    {
        $name = (new ReflectionClass($classname))->getShortName();

        return new self(sprintf("Unable to find enum cases on %s::class.", $name));
    }
}
