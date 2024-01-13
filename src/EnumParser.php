<?php

declare(strict_types=1);

namespace Enderive\Enum;

use Enderive\Enum\Validation\EnumValidator;
use Enderive\Enum\Exceptions\InvalidEnumException;
use ReflectionClass;

class EnumParser
{
    /**
     * Regex pattern for PHPDoc @method tag.
     * Match: "@method static Suit CLUBS()"
     *
     * @var non-empty-string
     */
    private static $pattern = '~\@method\sstatic\s.+\s(\w+)\(\)~';

    /**
     * Extract all of the cases and values from the enum class.
     *
     * @param class-string $classname
     * @throws InvalidEnumException
     *
     * @return array<string,int|string|null>
     */
    public static function parse(string $classname)
    {
        $reflection = new ReflectionClass($classname);

        if ($constants = $reflection->getConstants()) {
            // If backed enum values are present we will pass them through validation
            // chain to ensure there are no duplicated values, invalid data types and so on.
            EnumValidator::validate($classname, $constants);

            /** @var array<string,int|string|null> */
            return $constants;
        }

        $comment = (string) $reflection->getDocComment();

        // If cases are not defined on the class itself we will try to extract
        // them from the DocBlocks comment using regular expressions.
        if (false != preg_match_all(self::$pattern, $comment, $matches)) {
            return array_fill_keys($matches[1], null);
        }

        throw InvalidEnumException::for($classname);
    }
}
