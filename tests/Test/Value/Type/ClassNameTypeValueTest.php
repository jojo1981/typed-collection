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

use Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase;
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
class ClassNameTypeValueTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\ClassNameTypeValue::getInvalidValueStrings
     *
     * @param string $value
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function isValidValueShouldReturnFalseForInvalidValue(string $value): void
    {
        $this->assertFalse(ClassNameTypeValue::isValidValue($value));
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\ClassNameTypeValue::getInvalidValueStrings
     *
     * @param string $value
     * @param string $message
     * @throws ValueException
     * @return void
     */
    public function constructWithInvalidTypeShouldThrowValueException(string $value, string $message): void
    {
        $this->expectExceptionObject(new ValueException($message));
        new ClassNameTypeValue($value);
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\ClassNameTypeValue::getValidValueStrings
     *
     * @param string $value
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function isValidValueShouldReturnTrueForValidValue(string $value): void
    {
        $this->assertTrue(ClassNameTypeValue::isValidValue($value));
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\ClassNameTypeValue::getValidValueStrings
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
        $classNameTypeValue = new ClassNameTypeValue($value);
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
        $classNameTypeValue1 = new ClassNameTypeValue(TestEntityBase::class);
        $classNameTypeValue2 = new ClassNameTypeValue(TestEntity::class);

        $primitiveTypeValue = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_STRING);

        $this->assertFalse($classNameTypeValue1->isEqual($classNameTypeValue2));
        $this->assertFalse($classNameTypeValue2->isEqual($primitiveTypeValue));

        $this->assertFalse($classNameTypeValue1->isEqual($primitiveTypeValue));
        $this->assertFalse($classNameTypeValue2->isEqual($classNameTypeValue1));
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
        $classNameTypeValue1 = new ClassNameTypeValue(TestEntity::class);
        $classNameTypeValue2 = new ClassNameTypeValue(TestEntity::class);

        $this->assertTrue($classNameTypeValue1->isEqual($classNameTypeValue1));
        $this->assertTrue($classNameTypeValue2->isEqual($classNameTypeValue2));

        $this->assertTrue($classNameTypeValue1->isEqual($classNameTypeValue2));
        $this->assertTrue($classNameTypeValue2->isEqual($classNameTypeValue1));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function matchShouldReturnFalse(): void
    {
        $classNameTypeValue1 = new ClassNameTypeValue(InterfaceTestEntity::class);
        $classNameTypeValue2 = new ClassNameTypeValue(AbstractTestEntity::class);
        $classNameTypeValue3 = new ClassNameTypeValue(TestEntityBase::class);
        $classNameTypeValue4 = new ClassNameTypeValue(TestEntity::class);

        $primitiveTypeValue = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_STRING);

        $this->assertFalse($classNameTypeValue1->match($primitiveTypeValue));
        $this->assertFalse($classNameTypeValue2->match($primitiveTypeValue));
        $this->assertFalse($classNameTypeValue3->match($primitiveTypeValue));
        $this->assertFalse($classNameTypeValue4->match($primitiveTypeValue));

        $this->assertFalse($classNameTypeValue4->match($classNameTypeValue3));
        $this->assertFalse($classNameTypeValue4->match($classNameTypeValue2));
        $this->assertFalse($classNameTypeValue4->match($classNameTypeValue1));

        $this->assertFalse($classNameTypeValue3->match($classNameTypeValue2));
        $this->assertFalse($classNameTypeValue3->match($classNameTypeValue1));

        $this->assertFalse($classNameTypeValue2->match($classNameTypeValue1));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function matchShouldReturnTrue(): void
    {
        $classNameTypeValue1 = new ClassNameTypeValue(InterfaceTestEntity::class);
        $classNameTypeValue2 = new ClassNameTypeValue(AbstractTestEntity::class);
        $classNameTypeValue3 = new ClassNameTypeValue(TestEntityBase::class);
        $classNameTypeValue4 = new ClassNameTypeValue(TestEntity::class);

        $this->assertTrue($classNameTypeValue1->match($classNameTypeValue1));
        $this->assertTrue($classNameTypeValue1->match($classNameTypeValue2));
        $this->assertTrue($classNameTypeValue1->match($classNameTypeValue3));
        $this->assertTrue($classNameTypeValue1->match($classNameTypeValue4));

        $this->assertTrue($classNameTypeValue2->match($classNameTypeValue2));
        $this->assertTrue($classNameTypeValue2->match($classNameTypeValue3));
        $this->assertTrue($classNameTypeValue2->match($classNameTypeValue4));

        $this->assertTrue($classNameTypeValue3->match($classNameTypeValue3));
        $this->assertTrue($classNameTypeValue3->match($classNameTypeValue4));

        $this->assertTrue($classNameTypeValue4->match($classNameTypeValue4));
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\ClassNameTypeValue::getIsValidDataTestData
     *
     * @param string $value
     * @param mixed $invalidData
     * @param ValidationResultInterface $expectValidationResult
     * @throws ValueException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function isValidDataShouldReturnReturnTheCorrectValidationResult(
        string $value,
        $invalidData,
        ValidationResultInterface $expectValidationResult
    ): void
    {
        $this->assertEquals($expectValidationResult, (new ClassNameTypeValue($value))->isValidData($invalidData));
    }
}