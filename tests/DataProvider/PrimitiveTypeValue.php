<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection\TestSuite\DataProvider;

use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase;
use Jojo1981\TypedCollection\Value\Exception\ValueException;
use Jojo1981\TypedCollection\Value\Validation\ErrorValidationResult;
use Jojo1981\TypedCollection\Value\Validation\SuccessValidationResult;

/**
 * @package Jojo1981\TypedCollection\TestSuite\DataProvider
 */
class PrimitiveTypeValue
{
    /**
     * @return array[]
     */
    public function getValidValueStrings(): array
    {
        // SIGNATURE: string $value, string $mappedValue

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
        // SIGNATURE: string $value, string $message

        return [
            ['invalid-type-value', 'Invalid type: `invalid-type-value` given. Valid types are [int, integer, float, double, number, bool, boolean, string, array, object]'],
            [TestEntity::class, 'Invalid type: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity` given. Valid types are [int, integer, float, double, number, bool, boolean, string, array, object]'],
            [\stdClass::class, 'Invalid type: `stdClass` given. Valid types are [int, integer, float, double, number, bool, boolean, string, array, object]']
        ];
    }

    /**
     * @throws ValueException
     * @return array[]
     */
    public function getIsValidDataTestData(): array
    {
        // SIGNATURE: string $value, mixed $data, ValidationResultInterface $expectValidationResult
        return [

            // Success

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

            // Error

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
            ['integer', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `integer`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`')],
            ['integer', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `integer`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`')],

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
            ['int', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `integer`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`')],
            ['int', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `integer`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`')],

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
            ['float', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`')],
            ['float', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`')],

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
            ['double', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`')],
            ['double', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`')],

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
            ['number', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`')],
            ['number', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `float`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`')],

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
            ['boolean', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `boolean`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`')],
            ['boolean', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `boolean`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`')],

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
            ['bool', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `boolean`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`')],
            ['bool', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `boolean`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`')],

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
            ['string', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `string`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`')],
            ['string', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `string`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`')],

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
            ['array', new TestEntity(), new ErrorValidationResult('Data is not of expected type: `array`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`')],
            ['array', new TestEntityBase(), new ErrorValidationResult('Data is not of expected type: `array`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`')],

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