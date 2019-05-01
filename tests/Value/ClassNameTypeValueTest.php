<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace tests\Jojo1981\TypedCollection\Value;

use Jojo1981\TypedCollection\Value\ClassNameTypeValue;
use Jojo1981\TypedCollection\Value\Exception\ValueException;
use Jojo1981\TypedCollection\Value\PrimitiveTypeValue;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use tests\Jojo1981\TypedCollection\Entity\AbstractTestEntity;
use tests\Jojo1981\TypedCollection\Entity\TestEntity;
use tests\Jojo1981\TypedCollection\Entity\TestEntityBase;

/**
 * @package tests\Jojo1981\TypedCollection\Value
 */
class ClassNameTypeValueTest extends TestCase
{
    /**
     * @test
     * @dataProvider getInvalidData
     *
     * @param string $value
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isValidValueShouldReturnFalseForInvalidValue(string $value): void
    {
        $this->assertFalse(ClassNameTypeValue::isValidValue($value));
    }

    /**
     * @test
     * @dataProvider getInvalidData
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
     * @dataProvider getValidData
     *
     * @param string $value
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isValidValueShouldReturnTrueForValidValue(string $value): void
    {
        $this->assertTrue(ClassNameTypeValue::isValidValue($value));
    }

    /**
     * @test
     * @dataProvider getValidData
     *
     * @param string $value
     * @param string $mappedValue
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
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
    public function matchShouldReturnFalseWhenTypeValueObjectAreNotMatching(): void
    {
        $classNameTypeValue1 = new ClassNameTypeValue(TestEntityBase::class);
        $classNameTypeValue2 = new ClassNameTypeValue(TestEntity::class);

        $primitiveTypeValue = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_STRING);

        $this->assertFalse($classNameTypeValue1->match($classNameTypeValue2));
        $this->assertFalse($classNameTypeValue2->match($primitiveTypeValue));

        $this->assertFalse($classNameTypeValue1->match($primitiveTypeValue));
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
    public function matchShouldReturnTrueWhenTypeValueObjectAreMatching(): void
    {
        $classNameTypeValue1 = new ClassNameTypeValue(TestEntity::class);
        $classNameTypeValue2 = new ClassNameTypeValue(TestEntity::class);

        $this->assertTrue($classNameTypeValue1->match($classNameTypeValue1));
        $this->assertTrue($classNameTypeValue2->match($classNameTypeValue2));

        $this->assertTrue($classNameTypeValue1->match($classNameTypeValue2));
        $this->assertTrue($classNameTypeValue2->match($classNameTypeValue1));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isValidDataShouldReturnFalseWhenDataIsNotConformTheClassNameType(): void
    {
        $classNameTypeValue1 = new ClassNameTypeValue(TestEntityBase::class);
        $classNameTypeValue2 = new ClassNameTypeValue(TestEntity::class);

        $this->assertFalse($classNameTypeValue1->isValidData(new TestEntity()));
        $this->assertFalse($classNameTypeValue2->isValidData(new TestEntityBase()));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isValidDataShouldReturnTrueWhenDataIsConformTheClassNameType(): void
    {
        $classNameTypeValue1 = new ClassNameTypeValue(TestEntityBase::class);
        $classNameTypeValue2 = new ClassNameTypeValue(TestEntity::class);

        $this->assertTrue($classNameTypeValue1->isValidData(new TestEntityBase()));
        $this->assertTrue($classNameTypeValue2->isValidData(new TestEntity()));
    }

    /**
     * @return array[]
     */
    public function getInvalidData(): array
    {
        return [
            ['', 'Value can not be empty'],
            ['in-valid-type', 'Invalid class name: `in-valid-type`'],
            ['int', 'Invalid class name: `int`'],
            ['integer', 'Invalid class name: `integer`'],
            ['float', 'Invalid class name: `float`'],
            ['double', 'Invalid class name: `double`'],
            ['number', 'Invalid class name: `number`'],
            ['bool', 'Invalid class name: `bool`'],
            ['boolean', 'Invalid class name: `boolean`'],
            ['string', 'Invalid class name: `string`'],
            ['array', 'Invalid class name: `array`'],
            ['object', 'Invalid class name: `object`'],
            [
                'tests\Jojo1981\TypedCollection\Value',
                'Invalid class name: `tests\Jojo1981\TypedCollection\Value`'
            ],
            [
                '\tests\Jojo1981\TypedCollection\Value',
                'Invalid class name: `\tests\Jojo1981\TypedCollection\Value`'
            ],
            [
                AbstractTestEntity::class,
                'Invalid existing class name: `' . AbstractTestEntity::class. '` it is not instantiable'
            ],
            [
                '\\' . AbstractTestEntity::class,
                'Invalid existing class name: `\\' . AbstractTestEntity::class. '` it is not instantiable'
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function getValidData(): array
    {
        return [
            [__CLASS__, __CLASS__],
            [ClassNameTypeValue::class, ClassNameTypeValue::class],
            ['\\' . ClassNameTypeValue::class, ClassNameTypeValue::class]
        ];
    }
}