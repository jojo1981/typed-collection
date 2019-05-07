<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection\TestSuite\Test\Value\Type;

use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity;
use Jojo1981\TypedCollection\Value\Exception\ValueException;
use Jojo1981\TypedCollection\Value\Type\ClassNameTypeValue;
use Jojo1981\TypedCollection\Value\Type\PrimitiveTypeValue;
use Jojo1981\TypedCollection\Value\Validation\ValidationResultInterface;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @package Jojo1981\TypedCollection\TestSuite\Test\Value\Type
 */
class PrimitiveTypeValueTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\PrimitiveTypeValue::getInvalidValueStrings
     *
     * @param string $value
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function isValidValueShouldReturnFalseForInvalidValue(string $value): void
    {
        $this->assertFalse(PrimitiveTypeValue::isValidValue($value));
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\PrimitiveTypeValue::getInvalidValueStrings
     *
     * @param string $value
     * @param string $message
     * @throws ValueException
     * @return void
     */
    public function constructWithInvalidTypeShouldThrowValueException(string $value, string $message): void
    {
        $this->expectExceptionObject(new ValueException($message));
        new PrimitiveTypeValue($value);
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\PrimitiveTypeValue::getValidValueStrings
     *
     * @param string $value
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function isValidValueShouldReturnTrueForValidValue(string $value): void
    {
        $this->assertTrue(PrimitiveTypeValue::isValidValue($value));
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\PrimitiveTypeValue::getValidValueStrings
     *
     * @param string $value
     * @param string $mappedValue
     * @throws ValueException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function constructWithValidValueShouldReturnCorrectMappedValue(string $value, string $mappedValue): void
    {
        $classNameTypeValue = new PrimitiveTypeValue($value);
        $this->assertEquals($mappedValue, $classNameTypeValue->getValue());
        $this->assertEquals($mappedValue, (string) $classNameTypeValue);
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isEqualShouldReturnFalseWhenTypeValueObjectAreNotMatching(): void
    {
        $primitiveTypeValue1 = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_INT);
        $primitiveTypeValue2 = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_STRING);

        $classNameTypeValue = new ClassNameTypeValue(TestEntity::class);

        $this->assertFalse($primitiveTypeValue1->isEqual($primitiveTypeValue2));
        $this->assertFalse($primitiveTypeValue2->isEqual($primitiveTypeValue1));

        $this->assertFalse($primitiveTypeValue1->isEqual($classNameTypeValue));
        $this->assertFalse($primitiveTypeValue2->isEqual($classNameTypeValue));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isEqualShouldReturnTrueWhenTypeValueObjectAreMatching(): void
    {
        $primitiveTypeValue1 = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_INT);
        $primitiveTypeValue2 = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_INTEGER);

        $this->assertTrue($primitiveTypeValue1->isEqual($primitiveTypeValue1));
        $this->assertTrue($primitiveTypeValue2->isEqual($primitiveTypeValue2));

        $this->assertTrue($primitiveTypeValue1->isEqual($primitiveTypeValue2));
        $this->assertTrue($primitiveTypeValue2->isEqual($primitiveTypeValue1));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function matchShouldReturnFalseWhenTypeValueObjectAreNotMatching(): void
    {
        $primitiveTypeValue1 = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_INT);
        $primitiveTypeValue2 = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_STRING);

        $classNameTypeValue = new ClassNameTypeValue(TestEntity::class);

        $this->assertFalse($primitiveTypeValue1->match($primitiveTypeValue2));
        $this->assertFalse($primitiveTypeValue2->match($primitiveTypeValue1));

        $this->assertFalse($primitiveTypeValue1->match($classNameTypeValue));
        $this->assertFalse($primitiveTypeValue2->match($classNameTypeValue));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function matchShouldReturnTrueWhenTypeValueObjectAreMatching(): void
    {
        $primitiveTypeValue1 = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_INT);
        $primitiveTypeValue2 = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_INTEGER);

        $this->assertTrue($primitiveTypeValue1->match($primitiveTypeValue1));
        $this->assertTrue($primitiveTypeValue2->match($primitiveTypeValue2));

        $this->assertTrue($primitiveTypeValue1->match($primitiveTypeValue2));
        $this->assertTrue($primitiveTypeValue2->match($primitiveTypeValue1));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function getValidValuesShouldReturnAllValidValues(): void
    {
        $this->assertEquals(
            ['int', 'integer', 'float', 'double', 'number', 'bool', 'boolean', 'string', 'array', 'object'],
            PrimitiveTypeValue::getValidValues()
        );
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\PrimitiveTypeValue::getIsValidDataTestData
     *
     * @param string $value
     * @param mixed $data
     * @param ValidationResultInterface $expectValidationResult
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ValueException
     * @return void
     */
    public function isValidDataShouldReturnReturnTheCorrectValidationResult(
        string $value,
        $data,
        ValidationResultInterface $expectValidationResult
    ): void
    {
        $this->assertEquals($expectValidationResult, (new PrimitiveTypeValue($value))->isValidData($data));
    }
}