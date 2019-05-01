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
     * @dataProvider getInvalidValueStrings
     *
     * @param string $value
     * @return void
     *@throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function isValidValueShouldReturnFalseForInvalidValue(string $value): void
    {
        $this->assertFalse(ClassNameTypeValue::isValidValue($value));
    }

    /**
     * @test
     * @dataProvider getInvalidValueStrings
     *
     * @param string $value
     * @param string $message
     * @return void
     *@throws ValueException
     */
    public function constructWithInvalidTypeShouldThrowValueException(string $value, string $message): void
    {
        $this->expectExceptionObject(new ValueException($message));
        new ClassNameTypeValue($value);
    }

    /**
     * @test
     * @dataProvider getValidValueStrings
     *
     * @param string $value
     * @return void
     *@throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function isValidValueShouldReturnTrueForValidValue(string $value): void
    {
        $this->assertTrue(ClassNameTypeValue::isValidValue($value));
    }

    /**
     * @test
     * @dataProvider getValidValueStrings
     *
     * @param string $value
     * @param string $mappedValue
     * @return void
     *@throws ValueException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
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
     * @dataProvider getInvalidDataMap
     *
     * @param string $value
     * @param mixed $invalidData
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isValidDataShouldReturnFalseWhenDataIsNotConformTheClassNameType(
        string $value,
        $invalidData
    ): void
    {
        $this->assertFalse((new ClassNameTypeValue($value))->isValidData($invalidData));
    }

    /**
     * @test
     * @dataProvider getValidDataMap
     *
     * @param string $value
     * @param mixed $validData
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isValidDataShouldReturnTrueWhenDataIsConformTheClassNameType(
        string $value,
        $validData
    ): void
    {
        $this->assertTrue((new ClassNameTypeValue($value))->isValidData($validData));
    }

    /**
     * @return array[]
     */
    public function getInvalidValueStrings(): array
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
    public function getValidValueStrings(): array
    {
        return [
            [__CLASS__, __CLASS__],
            [ClassNameTypeValue::class, ClassNameTypeValue::class],
            ['\\' . ClassNameTypeValue::class, ClassNameTypeValue::class]
        ];
    }


    /**
     * @return array[]
     */
    public function getValidDataMap(): array
    {
        return [
            [TestEntity::class, new TestEntity()],
            ['\\' . TestEntity::class, new TestEntity()],
            [TestEntityBase::class, new TestEntityBase()],
            ['\\' . TestEntityBase::class, new TestEntityBase()],
            [\stdClass::class, new \stdClass()]
        ];
    }

    /**
     * @return array[]
     */
    public function getInvalidDataMap(): array
    {
        return [
            [TestEntity::class, new TestEntityBase()],
            [TestEntity::class, new \stdClass()],
            [TestEntityBase::class, new TestEntity()],
            [TestEntityBase::class, new \stdClass()],
            [\stdClass::class, new TestEntityBase()],
            [\stdClass::class, new TestEntity()],
            [\stdClass::class, []],
            [\stdClass::class, ['item1', 'item2']],
            [\stdClass::class, ['key' => 'value']],
            [\stdClass::class, 1],
            [\stdClass::class, 1.0],
            [\stdClass::class, 0],
            [\stdClass::class, 0.0],
            [\stdClass::class, -5],
            [\stdClass::class, -5.0],
            [\stdClass::class, true],
            [\stdClass::class, false],
            [\stdClass::class, 'text'],
        ];
    }
}