<?php

declare(strict_types=1);

namespace Enderive\Enum\Validation\Rules;

use UnexpectedValueException;

/**
 * Ensure all of the enum values are of the same type.
 */
class SingleDataTypeRule implements RuleInterface
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
        $types = array_map('gettype', $array);

        if (count(array_unique($types)) == 1) {
            return;
        }

        throw new UnexpectedValueException(
            sprintf($this->message(), $classname)
        );
    }

    /**
     * Create exceptions message.
     *
     * @return string
     */
    private function message(): string
    {
        return 'All enum values must be of the same data type ("%s")';
    }
}
