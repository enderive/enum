<?php

declare(strict_types=1);

namespace Enderive\Enum\Tests;

use Enderive\Enum\Validation\Rules\SingleDataTypeRule;
use Enderive\Enum\Validation\Rules\UniqueValuesRule;
use Enderive\Enum\Validation\Rules\ValidDataTypeRule;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class EnumValidationTest extends TestCase
{
    public function testUniqueValuesRuleThrowsExceptionOnInvalidData()
    {
        $validator = new UniqueValuesRule();
        
        $this->assertIsCallable($validator);
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessageMatches('~All enum values must be unique~');

        $validator->__invoke('<classname>', [
            'FOO' => 'bar',
            'BAR' => 'bar'
        ]);
    }

    public function testValidDataTypeRuleThrowsExceptionOnInvalidData()
    {
        $validator = new ValidDataTypeRule();
        
        $this->assertIsCallable($validator);
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessageMatches('~enum backing type must be either integer or string~');

        $validator->__invoke('<classname>', [
            'FOO' => [],
            'BAR' => 'value'
        ]);
    }

    public function testSingleDataTypeRuleThrowsExceptionOnInvalidData()
    {
        $validator = new SingleDataTypeRule();
        
        $this->assertIsCallable($validator);
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessageMatches('~enum values must be of the same data type~');

        $validator->__invoke('<classname>', [
            'FOO' => 1,
            'BAR' => '1'
        ]);
    }
}
