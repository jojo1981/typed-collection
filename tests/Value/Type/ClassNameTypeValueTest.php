<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace tests\Jojo1981\TypedCollection\Value\Type;

use Jojo1981\TypedCollection\Value\Exception\ValueException;
use Jojo1981\TypedCollection\Value\Type\ClassNameTypeValue;
use Jojo1981\TypedCollection\Value\Type\PrimitiveTypeValue;
use Jojo1981\TypedCollection\Value\Validation\ErrorValidationResult;
use Jojo1981\TypedCollection\Value\Validation\SuccessValidationResult;
use Jojo1981\TypedCollection\Value\Validation\ValidationResultInterface;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use tests\Jojo1981\TypedCollection\Entity\AbstractTestEntity;
use tests\Jojo1981\TypedCollection\Entity\InterfaceTestEntity;
use tests\Jojo1981\TypedCollection\Entity\TestEntity;
use tests\Jojo1981\TypedCollection\Entity\TestEntityBase;

/**
 * @package tests\Jojo1981\TypedCollection\Value\Type
 */
class ClassNameTypeValueTest extends TestCase
{
    /**
     * @test
     * @dataProvider getInvalidValueStrings
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
     * @dataProvider getInvalidValueStrings
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
     * @dataProvider getValidValueStrings
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
     * @dataProvider getValidValueStrings
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
     * @dataProvider getIsValidDataTestData
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

    /**
     * @return array[]
     */
    public function getInvalidValueStrings(): array
    {
        return [
            ['', 'Value can not be empty'],
            ['in-valid-type', 'Invalid class name: `in-valid-type`. Value must be an existing class or interface.'],
            ['int', 'Invalid class name: `int`. Value must be an existing class or interface.'],
            ['integer', 'Invalid class name: `integer`. Value must be an existing class or interface.'],
            ['float', 'Invalid class name: `float`. Value must be an existing class or interface.'],
            ['double', 'Invalid class name: `double`. Value must be an existing class or interface.'],
            ['number', 'Invalid class name: `number`. Value must be an existing class or interface.'],
            ['bool', 'Invalid class name: `bool`. Value must be an existing class or interface.'],
            ['boolean', 'Invalid class name: `boolean`. Value must be an existing class or interface.'],
            ['string', 'Invalid class name: `string`. Value must be an existing class or interface.'],
            ['array', 'Invalid class name: `array`. Value must be an existing class or interface.'],
            ['object', 'Invalid class name: `object`. Value must be an existing class or interface.'],
            ['tests\Jojo1981\TypedCollection', 'Invalid class name: `tests\Jojo1981\TypedCollection`. Value must be an existing class or interface.']
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
            ['\\' . ClassNameTypeValue::class, ClassNameTypeValue::class],
            [AbstractTestEntity::class, AbstractTestEntity::class],
            [InterfaceTestEntity::class, InterfaceTestEntity::class]
        ];
    }

    /**
     * @throws ValueException
     * @return array[]
     */
    public function getIsValidDataTestData(): array
    {
        return [
            [TestEntityBase::class, new TestEntity(), new SuccessValidationResult()],
            [TestEntity::class, new TestEntity(), new SuccessValidationResult()],
            ['\\' . TestEntity::class, new TestEntity(), new SuccessValidationResult()],
            [TestEntityBase::class, new TestEntityBase(), new SuccessValidationResult()],
            ['\\' . TestEntityBase::class, new TestEntityBase(), new SuccessValidationResult()],
            [\stdClass::class, new \stdClass(), new SuccessValidationResult()],
            [TestEntity::class, new TestEntityBase(), new ErrorValidationResult('Data is not an instance of: `tests\Jojo1981\TypedCollection\Entity\TestEntity`, but an instance of: `tests\Jojo1981\TypedCollection\Entity\TestEntityBase`')],
            [TestEntity::class, new \stdClass(), new ErrorValidationResult('Data is not an instance of: `tests\Jojo1981\TypedCollection\Entity\TestEntity`, but an instance of: `stdClass`')],
            [TestEntityBase::class, new \stdClass(), new ErrorValidationResult('Data is not an instance of: `tests\Jojo1981\TypedCollection\Entity\TestEntityBase`, but an instance of: `stdClass`')],
            [\stdClass::class, new TestEntityBase(), new ErrorValidationResult('Data is not an instance of: `stdClass`, but an instance of: `tests\Jojo1981\TypedCollection\Entity\TestEntityBase`')],
            [\stdClass::class, [], new ErrorValidationResult('Data is not an instance of: `stdClass`, but of type: `array`')],
            [\stdClass::class, 'text', new ErrorValidationResult('Data is not an instance of: `stdClass`, but of type: `string`')],
            [\stdClass::class, -10, new ErrorValidationResult('Data is not an instance of: `stdClass`, but of type: `integer`')],
            [\stdClass::class, false, new ErrorValidationResult('Data is not an instance of: `stdClass`, but of type: `boolean`')],
            [\stdClass::class, true, new ErrorValidationResult('Data is not an instance of: `stdClass`, but of type: `boolean`')],
            [TestEntity::class, 0.0, new ErrorValidationResult('Data is not an instance of: `tests\Jojo1981\TypedCollection\Entity\TestEntity`, but of type: `float`')]
        ];
    }
}