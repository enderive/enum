<?php

declare(strict_types=1);

namespace Enderive\Enum\Tests;

use Enderive\Enum\EnumInterface;
use Enderive\Enum\Exceptions\ValueError;
use Enderive\Enum\Tests\Fixtures\IntegerBackedEnum;
use Enderive\Enum\Tests\Fixtures\StringBackedEnum;
use Enderive\Enum\Tests\Fixtures\PureEnum;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    public function testEnumsAreSingletons()
    {
        // Pure enum
        $this->assertSame(PureEnum::LINUX(), PureEnum::LINUX());

        // Backed enums
        $this->assertSame(StringBackedEnum::ADMIN(), StringBackedEnum::from('admin'));
        $this->assertSame(IntegerBackedEnum::from(1), IntegerBackedEnum::from('1'));
        
    }

    public function testCreatingPureEnumUsingStaticConstructor(): void
    {
        $instance = PureEnum::LINUX();

        $this->assertInstanceOf(PureEnum::class, $instance);
        $this->assertInstanceOf(EnumInterface::class, $instance);
    }

    public function testCreatingPureEnumFromInvalidValue(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid enumeration value: SYMBIAN');

        PureEnum::SYMBIAN();
    }

    public function testAccessingNameAndValueProperties()
    {
        $instance = PureEnum::LINUX();

        $this->assertNull($instance->value);
        $this->assertSame('LINUX', $instance->name);
    }

    public function testCannotCreateDynamicPropertiesOnEnum()
    {
        $this->expectException(\Error::class);

        StringBackedEnum::MEMBER()->bar = 'baz';
    }

    public function testAccessingUndefinedPropertiesTriggersError()
    {
        set_error_handler(function($errno, $errstr) {
            $this->assertEquals('Undefined property: $bar', $errstr);
        });

        StringBackedEnum::MEMBER()->bar;

        restore_error_handler();
    }

    public function testListingPureEnumCases()
    {
        $cases = PureEnum::cases();

        $this->assertSame([
            PureEnum::LINUX(),
            PureEnum::UNIX(),
            PureEnum::WINDOWS(),
            PureEnum::CHROMEOS()
        ], $cases);
    }

    public function testFromMethodThrowsValueError()
    {
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage('not a valid backing value for enum');

        IntegerBackedEnum::from('1a');
    }

    public function testPureEnumRegularExpression()
    {
        $this->assertMatchesRegularExpression(
            '~\@method\sstatic\s.+\s(\w+)\(\)~',
            '@method static System LINUX()'
        );
    }

    public function testTryFromMethod()
    {
        $this->assertInstanceOf(StringBackedEnum::class, StringBackedEnum::tryFrom('member'));
        $this->assertNull(StringBackedEnum::tryFrom('invalid'));
    }

    public function testCreatingBackedEnumUsingStaticConstructor(): void
    {
        $stringEnum = StringBackedEnum::ADMIN();
        $integerEnum = IntegerBackedEnum::MEMBER();

        $this->assertInstanceOf(StringBackedEnum::class, $stringEnum);
        $this->assertInstanceOf(EnumInterface::class, $stringEnum);

        $this->assertInstanceOf(IntegerBackedEnum::class, $integerEnum);
        $this->assertInstanceOf(EnumInterface::class, $integerEnum);
    }

    public function testCreatingBackedEnumUsingFromMethod()
    {
        $instance = StringBackedEnum::from('admin');

        $this->assertSame('ADMIN', $instance->name);
        $this->assertSame('admin', $instance->value);
        $this->assertInstanceOf(StringBackedEnum::class, $instance);
        $this->assertInstanceOf(EnumInterface::class, $instance);

        $this->assertInstanceOf(IntegerBackedEnum::class, IntegerBackedEnum::from(1));
        $this->assertInstanceOf(IntegerBackedEnum::class, IntegerBackedEnum::from('1'));
    }

    /**
     * @covers \Enderive\Enum\Enum::createCacheKey
     */
    public function testCacheKeyGenerator()
    {
        $reflection = new \ReflectionClass(PureEnum::class);

        $method = $reflection->getMethod('createCacheKey');
        $method->setAccessible(true);

        $key  = PureEnum::class . 'LINUX';
        $hash = $method->invoke($method, $key);
        
        $this->assertSame("39e7834fe5131f8ea8da0b349211219a", $hash);
    }
}
