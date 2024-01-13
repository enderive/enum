<?php

declare(strict_types=1);

namespace Enderive\Enum\Tests;

use Enderive\Enum\EnumParser;
use Enderive\Enum\Exceptions\InvalidEnumException;
use Enderive\Enum\Tests\Fixtures\InvalidEnum;
use Enderive\Enum\Tests\Fixtures\PureEnum;
use Enderive\Enum\Tests\Fixtures\StringBackedEnum;
use PHPUnit\Framework\TestCase;

class EnumParserTest extends TestCase
{
    public function testParsingDataOfPureEnum()
    {
        $data = EnumParser::parse(PureEnum::class);
        
        $this->assertSame([
            'LINUX' => null,
            'UNIX' => null,
            'WINDOWS' => null,
            'CHROMEOS' => null,
        ], $data);
    }

    public function testParsingDataOfBackedEnum()
    {
        $data = EnumParser::parse(StringBackedEnum::class);

        $this->assertSame([
            'ADMIN' => 'admin',
            'MEMBER' => 'member',
            'GUEST' => 'guest'
        ], $data);
    }

    public function testEnumParserThrowsExceptionOnInvalidEnum()
    {
        $this->expectException(InvalidEnumException::class);
        $this->expectExceptionMessage('Unable to find enum cases');

        EnumParser::parse(InvalidEnum::class);
    }
}