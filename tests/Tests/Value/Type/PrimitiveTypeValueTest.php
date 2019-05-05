<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace tests\Jojo1981\TypedCollection\Tests\Value\Type;

use Jojo1981\TypedCollection\Value\Exception\ValueException;
use Jojo1981\TypedCollection\Value\Type\ClassNameTypeValue;
use Jojo1981\TypedCollection\Value\Type\PrimitiveTypeValue;
use Jojo1981\TypedCollection\Value\Validation\ErrorValidationResult;
use Jojo1981\TypedCollection\Value\Validation\SuccessValidationResult;
use Jojo1981\TypedCollection\Value\Validation\ValidationResultInterface;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use tests\Jojo1981\TypedCollection\Fixtures\TestEntity;
use tests\Jojo1981\TypedCollection\Fixtures\TestEntityBase;

/**
 * @package tests\Jojo1981\TypedCollection\Tests\Value\Type
 */
class PrimitiveTypeValueTest extends TestCase
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
        $this->assertFalse(PrimitiveTypeValue::isValidValue($value));
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
        new PrimitiveTypeValue($value);
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
        $this->assertTrue(PrimitiveTypeValue::isValidValue($value));
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
     * @dataProvider getIsValidDataTestData
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

    /**
     * @return array[]
     */
    public function getValidValueStrings(): array
    {
        return [
            ['int', 'integer'],
            ['integer', 'integer'],
            ['float', 'float'],
            ['double', 'float'],
            ['number', 'float'],
            ['bool', 'boolean'],
            ['boolean', 'boolean'],
            ['string', 'string'],
            ['array', 'array'],
            ['object', 'object'],
            ['INT', 'integer'],
            ['INTEGER', 'integer'],
            ['FLOAT', 'float'],
            ['DOUBLE', 'float'],
            ['NUMBER', 'float'],
            ['BOOL', 'boolean'],
            ['BOOLEAN', 'boolean'],
            ['STRING', 'string'],
            ['ARRAY', 'array'],
            ['OBJECT', 'object']
        ];
    }

    /**
     * @return array[]
     */
    public function getInvalidValueStrings(): array
    {
        return [
            ['invalid-type-value', 'Invalid type: `invalid-type-value` given. Valid types are [int, integer, float, double, number, bool, boolean, string, array, object]'],
            [TestEntity::class, 'Invalid type: `tests\Jojo1981\TypedCollection\Fixtures\TestEntity` given. Valid types are [int, integer, float, double, number, bool, boolean, string, array, object]'],
            [\stdClass::class, 'Invalid type: `stdClass` given. Valid types are [int, integer, float, double, number, bool, boolean, string, array, object]']
        ];
    }

    /**
     * @throws ValueException
     * @return array[]
     */
    public function getIsValidDataTestData(): array
    {
        return [

            // Successes

            ['integer', -1, new SuccessValidationResult()],
            ['integer', 0, new SuccessValidationResult()],
            ['integer', 1, new SuccessValidationResult()],
            ['integer', 10, new SuccessValidationResult()],
            ['integer', -10, new SuccessValidationResult()],

            ['int', -1, new SuccessValidationResult()],
            ['int', 0, new SuccessValidationResult()],
            ['int', 1, new SuccessValidationResult()],
            ['int', 10, new SuccessValidationResult()],
            ['int', -10, new SuccessValidationResult()],

            ['float', -1.0, new SuccessValidationResult()],
            ['float', 0.0, new SuccessValidationResult()],
            ['float', 1.0, new SuccessValidationResult()],
            ['float', 10.0, new SuccessValidationResult()],
            ['float', -10.0, new SuccessValidationResult()],

            ['double', -1.0, new SuccessValidationResult()],
            ['double', 0.0, new SuccessValidationResult()],
            ['double', 1.0, new SuccessValidationResult()],
            ['double', 10.0, new SuccessValidationResult()],
            ['double', -10.0, new SuccessValidationResult()],

            ['number', -1.0, new SuccessValidationResult()],
            ['number', 0.0, new SuccessValidationResult()],
            ['number', 1.0, new SuccessValidationResult()],
            ['number', 10.0, new SuccessValidationResult()],
            ['number', -10.0, new SuccessValidationResult()],

            ['boolean', true, new SuccessValidationResult()],
            ['boolean', false, new SuccessValidationResult()],

            ['bool', true, new SuccessValidationResult()],
            ['bool', false, new SuccessValidationResult()],

            ['string', 'text1', new SuccessValidationResult()],
            ['string', 'text2', new SuccessValidationResult()],
            ['string', 'text3', new SuccessValidationResult()],
            ['string', TestEntity::class, new SuccessValidationResult()],

            ['array', [], new SuccessValidationResult()],
            ['array', ['item1', 'item2'], new SuccessValidationResult()],
            ['array', ['key' => 'value'], new SuccessValidationResult()],

            ['object', new \stdClass(), new SuccessValidationResult()],
            ['object', new TestEntity(), new SuccessValidationResult()],
            ['object', new TestEntityBase(), new SuccessValidationResult()],

            // Errors

            ['integer', 3.47, new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `float`')],
            ['integer', -5.23, new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `float`')],
            ['integer', 5.0, new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `float`')],
            ['integer', -5.0, new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `float`')],
            ['integer', 'text', new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `string`')],
            ['integer', true, new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `boolean`')],
            ['integer', false, new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `boolean`')],
            ['integer', [], new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `array`')],
            ['integer', ['item1', 'item2'], new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `array`')],
            ['integer', ['key' => ' value'], new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `array`')],
            ['integer', new \stdClass(), new ErrorValidationResult('Data is not of expected type: `integer`, but an instance of: `stdClass`')],
            ['integer', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `integer`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntity`')],
            ['integer', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `integer`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntityBase`')],

            ['int', 3.47, new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `float`')],
            ['int', -5.23, new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `float`')],
            ['int', 5.0, new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `float`')],
            ['int', -5.0, new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `float`')],
            ['int', 'text', new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `string`')],
            ['int', true, new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `boolean`')],
            ['int', false, new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `boolean`')],
            ['int', [], new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `array`')],
            ['int', ['item1', 'item2'], new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `array`')],
            ['int', ['key' => ' value'], new ErrorValidationResult('Data is not of expected type: `integer`, but of type: `array`')],
            ['int', new \stdClass(), new ErrorValidationResult('Data is not of expected type: `integer`, but an instance of: `stdClass`')],
            ['int', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `integer`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntity`')],
            ['int', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `integer`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntityBase`')],

            ['float', 0, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `integer`')],
            ['float', 1, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `integer`')],
            ['float', 10, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `integer`')],
            ['float', -3, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `integer`')],
            ['float', 'text', new ErrorValidationResult('Data is not of expected type: `float`, but of type: `string`')],
            ['float', true, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `boolean`')],
            ['float', false, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `boolean`')],
            ['float', [], new ErrorValidationResult('Data is not of expected type: `float`, but of type: `array`')],
            ['float', ['item1', 'item2'], new ErrorValidationResult('Data is not of expected type: `float`, but of type: `array`')],
            ['float', ['key' => ' value'], new ErrorValidationResult('Data is not of expected type: `float`, but of type: `array`')],
            ['float', new \stdClass(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `stdClass`')],
            ['float', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntity`')],
            ['float', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntityBase`')],

            ['double', 0, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `integer`')],
            ['double', 1, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `integer`')],
            ['double', 10, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `integer`')],
            ['double', -3, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `integer`')],
            ['double', 'text', new ErrorValidationResult('Data is not of expected type: `float`, but of type: `string`')],
            ['double', true, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `boolean`')],
            ['double', false, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `boolean`')],
            ['double', [], new ErrorValidationResult('Data is not of expected type: `float`, but of type: `array`')],
            ['double', ['item1', 'item2'], new ErrorValidationResult('Data is not of expected type: `float`, but of type: `array`')],
            ['double', ['key' => ' value'], new ErrorValidationResult('Data is not of expected type: `float`, but of type: `array`')],
            ['double', new \stdClass(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `stdClass`')],
            ['double', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntity`')],
            ['double', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntityBase`')],

            ['number', 0, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `integer`')],
            ['number', 1, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `integer`')],
            ['number', 10, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `integer`')],
            ['number', -3, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `integer`')],
            ['number', 'text', new ErrorValidationResult('Data is not of expected type: `float`, but of type: `string`')],
            ['number', true, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `boolean`')],
            ['number', false, new ErrorValidationResult('Data is not of expected type: `float`, but of type: `boolean`')],
            ['number', [], new ErrorValidationResult('Data is not of expected type: `float`, but of type: `array`')],
            ['number', ['item1', 'item2'], new ErrorValidationResult('Data is not of expected type: `float`, but of type: `array`')],
            ['number', ['key' => ' value'], new ErrorValidationResult('Data is not of expected type: `float`, but of type: `array`')],
            ['number', new \stdClass(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `stdClass`')],
            ['number', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntity`')],
            ['number', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntityBase`')],

            ['boolean', 0, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `integer`')],
            ['boolean', 1, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `integer`')],
            ['boolean', 10, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `integer`')],
            ['boolean', -3, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `integer`')],
            ['boolean', 3.47, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `float`')],
            ['boolean', -5.23, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `float`')],
            ['boolean', 5.0, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `float`')],
            ['boolean', -5.0, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `float`')],
            ['boolean', 'text', new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `string`')],
            ['boolean', [], new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `array`')],
            ['boolean', ['item1', 'item2'], new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `array`')],
            ['boolean', ['key' => ' value'], new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `array`')],
            ['boolean', new \stdClass(), new ErrorValidationResult('Data is not of expected type: `boolean`, but an instance of: `stdClass`')],
            ['boolean', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `boolean`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntity`')],
            ['boolean', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `boolean`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntityBase`')],

            ['bool', 0, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `integer`')],
            ['bool', 1, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `integer`')],
            ['bool', 10, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `integer`')],
            ['bool', -3, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `integer`')],
            ['bool', 3.47, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `float`')],
            ['bool', -5.23, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `float`')],
            ['bool', 5.0, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `float`')],
            ['bool', -5.0, new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `float`')],
            ['bool', 'text', new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `string`')],
            ['bool', [], new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `array`')],
            ['bool', ['item1', 'item2'], new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `array`')],
            ['bool', ['key' => ' value'], new ErrorValidationResult('Data is not of expected type: `boolean`, but of type: `array`')],
            ['bool', new \stdClass(), new ErrorValidationResult('Data is not of expected type: `boolean`, but an instance of: `stdClass`')],
            ['bool', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `boolean`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntity`')],
            ['bool', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `boolean`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntityBase`')],

            ['string', 0, new ErrorValidationResult('Data is not of expected type: `string`, but of type: `integer`')],
            ['string', 1, new ErrorValidationResult('Data is not of expected type: `string`, but of type: `integer`')],
            ['string', 10, new ErrorValidationResult('Data is not of expected type: `string`, but of type: `integer`')],
            ['string', -3, new ErrorValidationResult('Data is not of expected type: `string`, but of type: `integer`')],
            ['string', 3.47, new ErrorValidationResult('Data is not of expected type: `string`, but of type: `float`')],
            ['string', -5.23, new ErrorValidationResult('Data is not of expected type: `string`, but of type: `float`')],
            ['string', 5.0, new ErrorValidationResult('Data is not of expected type: `string`, but of type: `float`')],
            ['string', -5.0, new ErrorValidationResult('Data is not of expected type: `string`, but of type: `float`')],
            ['string', true, new ErrorValidationResult('Data is not of expected type: `string`, but of type: `boolean`')],
            ['string', false, new ErrorValidationResult('Data is not of expected type: `string`, but of type: `boolean`')],
            ['string', [], new ErrorValidationResult('Data is not of expected type: `string`, but of type: `array`')],
            ['string', ['item1', 'item2'], new ErrorValidationResult('Data is not of expected type: `string`, but of type: `array`')],
            ['string', ['key' => ' value'], new ErrorValidationResult('Data is not of expected type: `string`, but of type: `array`')],
            ['string', new \stdClass(), new ErrorValidationResult('Data is not of expected type: `string`, but an instance of: `stdClass`')],
            ['string', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `string`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntity`')],
            ['string', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `string`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntityBase`')],

            ['array', 0, new ErrorValidationResult('Data is not of expected type: `array`, but of type: `integer`')],
            ['array', 1, new ErrorValidationResult('Data is not of expected type: `array`, but of type: `integer`')],
            ['array', 10, new ErrorValidationResult('Data is not of expected type: `array`, but of type: `integer`')],
            ['array', -3, new ErrorValidationResult('Data is not of expected type: `array`, but of type: `integer`')],
            ['array', 3.47, new ErrorValidationResult('Data is not of expected type: `array`, but of type: `float`')],
            ['array', -5.23, new ErrorValidationResult('Data is not of expected type: `array`, but of type: `float`')],
            ['array', 5.0, new ErrorValidationResult('Data is not of expected type: `array`, but of type: `float`')],
            ['array', -5.0, new ErrorValidationResult('Data is not of expected type: `array`, but of type: `float`')],
            ['array', 'text', new ErrorValidationResult('Data is not of expected type: `array`, but of type: `string`')],
            ['array', true, new ErrorValidationResult('Data is not of expected type: `array`, but of type: `boolean`')],
            ['array', false, new ErrorValidationResult('Data is not of expected type: `array`, but of type: `boolean`')],
            ['array', new \stdClass(), new ErrorValidationResult('Data is not of expected type: `array`, but an instance of: `stdClass`')],
            ['array', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `array`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntity`')],
            ['array', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `array`, but an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntityBase`')],

            ['object', 0, new ErrorValidationResult('Data is not of expected type: `object`, but of type: `integer`')],
            ['object', 1, new ErrorValidationResult('Data is not of expected type: `object`, but of type: `integer`')],
            ['object', 10, new ErrorValidationResult('Data is not of expected type: `object`, but of type: `integer`')],
            ['object', -3, new ErrorValidationResult('Data is not of expected type: `object`, but of type: `integer`')],
            ['object', 3.47, new ErrorValidationResult('Data is not of expected type: `object`, but of type: `float`')],
            ['object', -5.23, new ErrorValidationResult('Data is not of expected type: `object`, but of type: `float`')],
            ['object', 5.0, new ErrorValidationResult('Data is not of expected type: `object`, but of type: `float`')],
            ['object', -5.0, new ErrorValidationResult('Data is not of expected type: `object`, but of type: `float`')],
            ['object', 'text', new ErrorValidationResult('Data is not of expected type: `object`, but of type: `string`')],
            ['object', true, new ErrorValidationResult('Data is not of expected type: `object`, but of type: `boolean`')],
            ['object', false, new ErrorValidationResult('Data is not of expected type: `object`, but of type: `boolean`')],
            ['object', [], new ErrorValidationResult('Data is not of expected type: `object`, but of type: `array`')],
            ['object', ['item1', 'item2'], new ErrorValidationResult('Data is not of expected type: `object`, but of type: `array`')],
            ['object', ['key' => ' value'], new ErrorValidationResult('Data is not of expected type: `object`, but of type: `array`')]
        ];
    }
}