<?php

declare(strict_types=1);

namespace Enderive\Enum;

use Enderive\Enum\Exceptions\ValueError;
use ErrorException;
use InvalidArgumentException;
use JsonSerializable;

/**
 * Simple enum implementation for php < 8.1.
 *
 * @author Enderive <matthias.sz@outlook.com>
 * @psalm-consistent-constructor
 */
abstract class Enum implements EnumInterface, JsonSerializable
{
    /**
     * Enum case name.
     *
     * @var string
     */
    private $name;

    /**
     * Backed enum value or null in case of pure enum.
     *
     * @var string|int|null
     */
    private $value;

    /**
     * Cache for instantiated enums.
     *
     * @var array<string,static>
     */
    private static $instances = [];

    /**
     * Cache for enums constants.
     *
     * @var array<string,array<string, int|string|null>>
     */
    private static $constants = [];

    /**
     * @param string $name
     * @param string|int|null $value
     */
    final private function __construct(string $name, $value = null)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    final public static function from($value)
    {
        if (!is_int($value) && !is_string($value)) {
            throw ValueError::forInvalidDataType($value);
        }

        $cases = self::toArray();
        $case  = array_search($value, $cases, false);

        // In native enum cases backed by integers can be instantiated from the
        // numeric strings and the opposite, for example (1 => '1' / '1' => 1).
        if (false === $case || (string) $cases[$case] != (string) $value) {
            throw ValueError::forInvalidCase(static::class, $value);
        }

        return self::create($case, $value);
    }

    final public static function tryFrom($value)
    {
        try {
            return self::from($value);
        } catch (ValueError $e) {
            return null;
        }
    }

    final public static function cases(): array
    {
        $return = [];

        foreach (self::toArray() as $case => $value) {
            $return[] = self::create($case, $value);
        }

        return $return;
    }

    /**
     * Get enum instance.
     *
     * @param string $case
     * @param int|string|null $value
     * @return static
     */
    private static function create($case, $value = null): static
    {
        $key = self::createCacheKey(static::class . $case);

        return self::$instances[$key] ?? self::$instances[$key] = new static($case, $value);
    }

    /**
     * Get the array of name-value pairs of class constants.
     *
     * @return array<string,int|string|null>
     */
    private static function toArray(): array
    {
        $key  = self::createCacheKey(static::class);

        if (isset(self::$constants[$key])) {
            return self::$constants[$key];
        }

        $constants = EnumParser::parse(static::class);

        return self::$constants[$key] = $constants;
    }

    /**
     * Create hash for caching purposes.
     *
     * @param string $key
     * @return string
     */
    private static function createCacheKey(string $key): string
    {
        return md5($key);
    }

    /**
     * @param string $name
     * @return string|int|null
     *
     * @psalm-api
     */
    final public function __get(string $name)
    {
        switch ($name) {
            case 'name':
                return $this->name;
            case 'value':
                return $this->value;
        }

        throw new ErrorException("Undefined property: $$name");
    }

    /**
     * Prevent dynamic properties to be set.
     *
     * @param string $_name
     * @param string $_value
     * @throws ErrorException
     * @return void
     *
     * @psalm-api
     */
    final public function __set($_name, $_value): void
    {
        throw new ErrorException("Dynamic properties are not allowed on enums.");
    }

    /**
     * Create enum instance with static method call, for example User::ADMIN().
     *
     * @param string $case
     * @param array<mixed> $_arguments
     * @return static
     *
     * @throws InvalidArgumentException
     *
     * @psalm-api
     */
    final public static function __callStatic(string $case, array $_arguments = [])
    {
        $cases = self::toArray();

        if (false === array_key_exists($case, $cases)) {
            throw new InvalidArgumentException("Invalid enumeration value: $case");
        }

        return self::create($case, $cases[$case]);
    }

    /**
     * Serialize enum to JSON.
     *
     * @return mixed
     *
     * @codeCoverageIgnore
     * @psalm-api
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->value;
    }
}
