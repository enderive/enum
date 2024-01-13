<?php

declare(strict_types=1);

namespace Enderive\Enum\Validation\Rules;

use UnexpectedValueException;

/**
 * Backed Enum values must be unique.
 */
class UniqueValuesRule implements RuleInterface
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

        if (count($array) === count(array_unique($array))) {
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
        return 'All enum values must be unique ("%s")';
    }
}
