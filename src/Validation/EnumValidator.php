<?php

declare(strict_types=1);

namespace Enderive\Enum\Validation;

use Enderive\Enum\Validation\Rules\RuleInterface;
use Enderive\Enum\Validation\Rules\SingleDataTypeRule;
use Enderive\Enum\Validation\Rules\ValidDataTypeRule;
use Enderive\Enum\Validation\Rules\UniqueValuesRule;

class EnumValidator
{
    /**
     * Array of enum rules.
     *
     * @var array<class-string<RuleInterface>>
     */
    protected static $rules = [
        ValidDataTypeRule::class,
        SingleDataTypeRule::class,
        UniqueValuesRule::class
    ];

    /**
     * @param class-string $classname
     * @param array<string,mixed> $values
     *
     * @return void
     */
    public static function validate(string $classname, array $values): void
    {
        foreach (static::$rules as $rule) {
            (new $rule())($classname, $values);
        }
    }
}
