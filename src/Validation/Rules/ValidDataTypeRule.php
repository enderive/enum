<?php

declare(strict_types=1);

namespace Enderive\Enum\Validation\Rules;

use UnexpectedValueException;

/**
 * Make sure enum is only backed by integers or strings.
 */
class ValidDataTypeRule implements RuleInterface
{
    /**
     * @param class-string $classname
     * @param array<string,mixed> $array
     * @throws UnexpectedValueException
     *
     * @return void
     */
    public function __invoke(string $classname, array $array): void
    {
        foreach ($array as $value) {
            if (!is_int($value) && !is_string($value)) {
                throw new UnexpectedValueException(
                    sprintf($this->message(), $classname, gettype($value))
                );    
            }            
        }
    }

    /**
     * Create exceptions message.
     *
     * @return string
     */
    private function message(): string
    {
        return '"%s" enum backing type must be either integer or string, %s given.';
    }
}
