<?php

declare(strict_types=1);

namespace Enderive\Enum\Validation\Rules;

use UnexpectedValueException;

interface RuleInterface
{
    /**
     * @param class-string $classname
     * @param array<string,mixed> $array
     * @throws UnexpectedValueException
     *
     * @return void
     */
    public function __invoke(string $classname, array $array): void;
}
