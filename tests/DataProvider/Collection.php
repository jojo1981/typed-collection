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

use Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase;

/**
 * @package Jojo1981\TypedCollection\TestSuite\DataProvider
 */
class Collection
{
    /**
     * @return array[]
     */
    public function getValidTypesMap(): array
    {
        // SIGNATURE: string $type, string $expectedType

        return [
            ['int', 'integer'],
            ['integer', 'integer'],
            ['bool', 'boolean'],
            ['boolean', 'boolean'],
            ['float', 'float'],
            ['double', 'float'],
            ['number', 'float'],
            ['string', 'string'],
            ['array', 'array'],
            ['object', 'object'],
            [TestEntity::class , TestEntity::class],
            [TestEntityBase::class, TestEntityBase::class],
            [AbstractTestEntity::class, AbstractTestEntity::class],
            [InterfaceTestEntity::class, InterfaceTestEntity::class]
        ];
    }

    /**
     * @return array[]
     */
    public function getStrictlyMatchingTypes(): array
    {
        // SIGNATURE: string $typeA, string $typeB

        return [
            ['int', 'int'],
            ['integer', 'integer'],
            ['integer', 'int'],
            ['int', 'integer'],
            ['string', 'string'],
            ['boolean', 'boolean'],
            ['bool', 'bool'],
            ['bool', 'boolean'],
            ['boolean', 'bool'],
            ['float', 'float'],
            ['float', 'double'],
            ['float', 'number'],
            ['double', 'double'],
            ['double', 'number'],
            ['double', 'float'],
            ['number', 'number'],
            ['number', 'double'],
            ['number', 'float'],
            ['array', 'array'],
            ['object', 'object'],
            [TestEntity::class , TestEntity::class],
            [TestEntityBase::class, TestEntityBase::class],
            [AbstractTestEntity::class, AbstractTestEntity::class],
            [InterfaceTestEntity::class, InterfaceTestEntity::class]
        ];
    }

    /**
     * @return array[]
     */
    public function getNotMatchingTypes(): array
    {
        // SIGNATURE: string $typeA, string $typeB

        return [
            ['string', 'int'],
            ['string', 'integer'],
            ['string', 'bool'],
            ['string', 'boolean'],
            ['string', 'float'],
            ['string', 'double'],
            ['string', 'number'],
            ['string', 'array'],
            ['string', 'object'],

            ['int', 'string'],
            ['int', 'bool'],
            ['int', 'boolean'],
            ['int', 'float'],
            ['int', 'double'],
            ['int', 'number'],
            ['int', 'array'],
            ['int', 'object'],

            ['integer', 'string'],
            ['integer', 'bool'],
            ['integer', 'boolean'],
            ['integer', 'float'],
            ['integer', 'double'],
            ['integer', 'number'],
            ['integer', 'array'],
            ['integer', 'object'],

            ['bool', 'string'],
            ['bool', 'int'],
            ['bool', 'integer'],
            ['bool', 'float'],
            ['bool', 'double'],
            ['bool', 'number'],
            ['bool', 'array'],
            ['bool', 'object'],

            ['boolean', 'string'],
            ['boolean', 'int'],
            ['boolean', 'integer'],
            ['boolean', 'float'],
            ['boolean', 'double'],
            ['boolean', 'number'],
            ['boolean', 'array'],
            ['boolean', 'object'],

            ['float', 'string'],
            ['float', 'int'],
            ['float', 'integer'],
            ['float', 'bool'],
            ['float', 'boolean'],
            ['float', 'array'],
            ['float', 'object'],

            ['double', 'string'],
            ['double', 'int'],
            ['double', 'integer'],
            ['double', 'bool'],
            ['double', 'boolean'],
            ['double', 'array'],
            ['double', 'object'],

            ['number', 'string'],
            ['number', 'int'],
            ['number', 'integer'],
            ['number', 'bool'],
            ['number', 'boolean'],
            ['number', 'array'],
            ['number', 'object'],

            ['array', 'string'],
            ['array', 'int'],
            ['array', 'integer'],
            ['array', 'bool'],
            ['array', 'boolean'],
            ['array', 'float'],
            ['array', 'double'],
            ['array', 'number'],
            ['array', 'object'],

            ['object', 'string'],
            ['object', 'int'],
            ['object', 'integer'],
            ['object', 'bool'],
            ['object', 'boolean'],
            ['object', 'float'],
            ['object', 'double'],
            ['object', 'number'],
            ['object', 'array'],

            [InterfaceTestEntity::class, AbstractTestEntity::class],
            [InterfaceTestEntity::class, TestEntityBase::class],
            [InterfaceTestEntity::class, TestEntity::class],
            [AbstractTestEntity::class, InterfaceTestEntity::class],
            [AbstractTestEntity::class, TestEntityBase::class],
            [AbstractTestEntity::class, TestEntity::class],
            [TestEntityBase::class, InterfaceTestEntity::class],
            [TestEntityBase::class, AbstractTestEntity::class],
            [TestEntityBase::class, TestEntity::class],
            [TestEntity::class, InterfaceTestEntity::class],
            [TestEntity::class, AbstractTestEntity::class],
            [TestEntity::class, TestEntityBase::class],

        ];
    }

    /**
     * @return array[]
     */
    public function getPrimitiveTypeWithInvalidData(): array
    {
        // SIGNATURE: string $type, array $invalidData, string $message

        return [

            ['int', [-1, 0, 1, 5, 'text'], 'Data is not of expected type: `integer`, but of type: `string`'],
            ['int', [-1, 0, 1, 5, -1.0], 'Data is not of expected type: `integer`, but of type: `float`'],
            ['int', [-1, 0, 1, 5, 0.0], 'Data is not of expected type: `integer`, but of type: `float`'],
            ['int', [-1, 0, 1, 5, 1.0], 'Data is not of expected type: `integer`, but of type: `float`'],
            ['int', [-1, 0, 1, 5, true], 'Data is not of expected type: `integer`, but of type: `boolean`'],
            ['int', [-1, 0, 1, 5, false], 'Data is not of expected type: `integer`, but of type: `boolean`'],
            ['int', [-1, 0, 1, 5, []], 'Data is not of expected type: `integer`, but of type: `array`'],
            ['int', [-1, 0, 1, 5, ['item1', 'item2']], 'Data is not of expected type: `integer`, but of type: `array`'],
            ['int', [-1, 0, 1, 5, ['key' => 'value']], 'Data is not of expected type: `integer`, but of type: `array`'],
            ['int', [-1, 0, 1, 5, new \stdClass()], 'Data is not of expected type: `integer`, but an instance of: `stdClass`'],
            ['int', [-1, 0, 1, 5, new TestEntity()], 'Data is not of expected type: `integer`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['int', [-1, 0, 1, 5, new TestEntityBase()], 'Data is not of expected type: `integer`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['integer', [-1, 0, 1, 5, 'text'], 'Data is not of expected type: `integer`, but of type: `string`'],
            ['integer', [-1, 0, 1, 5, -1.0], 'Data is not of expected type: `integer`, but of type: `float`'],
            ['integer', [-1, 0, 1, 5, 0.0], 'Data is not of expected type: `integer`, but of type: `float`'],
            ['integer', [-1, 0, 1, 5, 1.0], 'Data is not of expected type: `integer`, but of type: `float`'],
            ['integer', [-1, 0, 1, 5, true], 'Data is not of expected type: `integer`, but of type: `boolean`'],
            ['integer', [-1, 0, 1, 5, false], 'Data is not of expected type: `integer`, but of type: `boolean`'],
            ['integer', [-1, 0, 1, 5, []], 'Data is not of expected type: `integer`, but of type: `array`'],
            ['integer', [-1, 0, 1, 5, ['item1', 'item2']], 'Data is not of expected type: `integer`, but of type: `array`'],
            ['integer', [-1, 0, 1, 5, ['key' => 'value']], 'Data is not of expected type: `integer`, but of type: `array`'],
            ['integer', [-1, 0, 1, 5, new \stdClass()], 'Data is not of expected type: `integer`, but an instance of: `stdClass`'],
            ['integer', [-1, 0, 1, 5, new TestEntity()], 'Data is not of expected type: `integer`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['integer', [-1, 0, 1, 5, new TestEntityBase()], 'Data is not of expected type: `integer`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['float', [-1.0, 0.0, 1.0, 3.25, 'text'], 'Data is not of expected type: `float`, but of type: `string`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, -1], 'Data is not of expected type: `float`, but of type: `integer`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, 0], 'Data is not of expected type: `float`, but of type: `integer`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, 1], 'Data is not of expected type: `float`, but of type: `integer`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, true], 'Data is not of expected type: `float`, but of type: `boolean`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, false], 'Data is not of expected type: `float`, but of type: `boolean`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, []], 'Data is not of expected type: `float`, but of type: `array`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, ['item1', 'item2']], 'Data is not of expected type: `float`, but of type: `array`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, ['key' => 'value']], 'Data is not of expected type: `float`, but of type: `array`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, new \stdClass()], 'Data is not of expected type: `float`, but an instance of: `stdClass`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, new TestEntity()], 'Data is not of expected type: `float`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, new TestEntityBase()], 'Data is not of expected type: `float`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['double', [-1.0, 0.0, 1.0, 3.25, 'text'], 'Data is not of expected type: `float`, but of type: `string`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, -1], 'Data is not of expected type: `float`, but of type: `integer`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, 0], 'Data is not of expected type: `float`, but of type: `integer`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, 1], 'Data is not of expected type: `float`, but of type: `integer`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, true], 'Data is not of expected type: `float`, but of type: `boolean`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, false], 'Data is not of expected type: `float`, but of type: `boolean`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, []], 'Data is not of expected type: `float`, but of type: `array`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, ['item1', 'item2']], 'Data is not of expected type: `float`, but of type: `array`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, ['key' => 'value']], 'Data is not of expected type: `float`, but of type: `array`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, new \stdClass()], 'Data is not of expected type: `float`, but an instance of: `stdClass`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, new TestEntity()], 'Data is not of expected type: `float`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, new TestEntityBase()], 'Data is not of expected type: `float`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['number', [-1.0, 0.0, 1.0, 3.25, 'text'], 'Data is not of expected type: `float`, but of type: `string`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, -1], 'Data is not of expected type: `float`, but of type: `integer`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, 0], 'Data is not of expected type: `float`, but of type: `integer`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, 1], 'Data is not of expected type: `float`, but of type: `integer`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, true], 'Data is not of expected type: `float`, but of type: `boolean`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, false], 'Data is not of expected type: `float`, but of type: `boolean`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, []], 'Data is not of expected type: `float`, but of type: `array`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, ['item1', 'item2']], 'Data is not of expected type: `float`, but of type: `array`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, ['key' => 'value']], 'Data is not of expected type: `float`, but of type: `array`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, new \stdClass()], 'Data is not of expected type: `float`, but an instance of: `stdClass`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, new TestEntity()], 'Data is not of expected type: `float`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, new TestEntityBase()], 'Data is not of expected type: `float`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['string', ['text', -1], 'Data is not of expected type: `string`, but of type: `integer`'],
            ['string', ['text', 0], 'Data is not of expected type: `string`, but of type: `integer`'],
            ['string', ['text', 1], 'Data is not of expected type: `string`, but of type: `integer`'],
            ['string', ['text', -1.0], 'Data is not of expected type: `string`, but of type: `float`'],
            ['string', ['text', 0.0], 'Data is not of expected type: `string`, but of type: `float`'],
            ['string', ['text', 1.0], 'Data is not of expected type: `string`, but of type: `float`'],
            ['string', ['text', true], 'Data is not of expected type: `string`, but of type: `boolean`'],
            ['string', ['text', false], 'Data is not of expected type: `string`, but of type: `boolean`'],
            ['string', ['text', []], 'Data is not of expected type: `string`, but of type: `array`'],
            ['string', ['text', ['item1', 'item2']], 'Data is not of expected type: `string`, but of type: `array`'],
            ['string', ['text', ['key' => 'value']], 'Data is not of expected type: `string`, but of type: `array`'],
            ['string', ['text', new \stdClass()], 'Data is not of expected type: `string`, but an instance of: `stdClass`'],
            ['string', ['text', new TestEntity()], 'Data is not of expected type: `string`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['string', ['text', new TestEntityBase()], 'Data is not of expected type: `string`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['boolean', [true, false, 'text'], 'Data is not of expected type: `boolean`, but of type: `string`'],
            ['boolean', [true, false, -1], 'Data is not of expected type: `boolean`, but of type: `integer`'],
            ['boolean', [true, false, 0], 'Data is not of expected type: `boolean`, but of type: `integer`'],
            ['boolean', [true, false, 1], 'Data is not of expected type: `boolean`, but of type: `integer`'],
            ['boolean', [true, false, -1.0], 'Data is not of expected type: `boolean`, but of type: `float`'],
            ['boolean', [true, false, 0.0], 'Data is not of expected type: `boolean`, but of type: `float`'],
            ['boolean', [true, false, 1.0], 'Data is not of expected type: `boolean`, but of type: `float`'],
            ['boolean', [true, false, []], 'Data is not of expected type: `boolean`, but of type: `array`'],
            ['boolean', [true, false, ['item1', 'item2']], 'Data is not of expected type: `boolean`, but of type: `array`'],
            ['boolean', [true, false, ['key' => 'value']], 'Data is not of expected type: `boolean`, but of type: `array`'],
            ['boolean', [true, false, new \stdClass()], 'Data is not of expected type: `boolean`, but an instance of: `stdClass`'],
            ['boolean', [true, false, new TestEntity()], 'Data is not of expected type: `boolean`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['boolean', [true, false, new TestEntityBase()], 'Data is not of expected type: `boolean`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],
            
            ['bool', [true, false, 'text'], 'Data is not of expected type: `boolean`, but of type: `string`'],
            ['bool', [true, false, -1], 'Data is not of expected type: `boolean`, but of type: `integer`'],
            ['bool', [true, false, 0], 'Data is not of expected type: `boolean`, but of type: `integer`'],
            ['bool', [true, false, 1], 'Data is not of expected type: `boolean`, but of type: `integer`'],
            ['bool', [true, false, -1.0], 'Data is not of expected type: `boolean`, but of type: `float`'],
            ['bool', [true, false, 0.0], 'Data is not of expected type: `boolean`, but of type: `float`'],
            ['bool', [true, false, 1.0], 'Data is not of expected type: `boolean`, but of type: `float`'],
            ['bool', [true, false, []], 'Data is not of expected type: `boolean`, but of type: `array`'],
            ['bool', [true, false, ['item1', 'item2']], 'Data is not of expected type: `boolean`, but of type: `array`'],
            ['bool', [true, false, ['key' => 'value']], 'Data is not of expected type: `boolean`, but of type: `array`'],
            ['bool', [true, false, new \stdClass()], 'Data is not of expected type: `boolean`, but an instance of: `stdClass`'],
            ['bool', [true, false, new TestEntity()], 'Data is not of expected type: `boolean`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['bool', [true, false, new TestEntityBase()], 'Data is not of expected type: `boolean`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['array', [[], ['item1', 'item2'], ['key' => 'value'], 'text'], 'Data is not of expected type: `array`, but of type: `string`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], -1], 'Data is not of expected type: `array`, but of type: `integer`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], 0], 'Data is not of expected type: `array`, but of type: `integer`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], 1], 'Data is not of expected type: `array`, but of type: `integer`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], -1.0], 'Data is not of expected type: `array`, but of type: `float`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], 0.0], 'Data is not of expected type: `array`, but of type: `float`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], 1.0], 'Data is not of expected type: `array`, but of type: `float`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], true], 'Data is not of expected type: `array`, but of type: `boolean`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], false], 'Data is not of expected type: `array`, but of type: `boolean`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], new \stdClass()], 'Data is not of expected type: `array`, but an instance of: `stdClass`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], new TestEntity()], 'Data is not of expected type: `array`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], new TestEntityBase()], 'Data is not of expected type: `array`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['object', [new \stdClass(), new TestEntity(), new TestEntityBase(), 'text'], 'Data is not of expected type: `object`, but of type: `string`'],
            ['object', [new \stdClass(), new TestEntity(), new TestEntityBase(), -1], 'Data is not of expected type: `object`, but of type: `integer`'],
            ['object', [new \stdClass(), new TestEntity(), new TestEntityBase(), 0], 'Data is not of expected type: `object`, but of type: `integer`'],
            ['object', [new \stdClass(), new TestEntity(), new TestEntityBase(), 1], 'Data is not of expected type: `object`, but of type: `integer`'],
            ['object', [new \stdClass(), new TestEntity(), new TestEntityBase(), -1.0], 'Data is not of expected type: `object`, but of type: `float`'],
            ['object', [new \stdClass(), new TestEntity(), new TestEntityBase(), 0.0], 'Data is not of expected type: `object`, but of type: `float`'],
            ['object', [new \stdClass(), new TestEntity(), new TestEntityBase(), 1.0], 'Data is not of expected type: `object`, but of type: `float`'],
            ['object', [new \stdClass(), new TestEntity(), new TestEntityBase(), true], 'Data is not of expected type: `object`, but of type: `boolean`'],
            ['object', [new \stdClass(), new TestEntity(), new TestEntityBase(), false], 'Data is not of expected type: `object`, but of type: `boolean`'],
            ['object', [new \stdClass(), new TestEntity(), new TestEntityBase(), []], 'Data is not of expected type: `object`, but of type: `array`'],
            ['object', [new \stdClass(), new TestEntity(), new TestEntityBase(), ['item1', 'item2']], 'Data is not of expected type: `object`, but of type: `array`'],
            ['object', [new \stdClass(), new TestEntity(), new TestEntityBase(), ['key' => 'value']], 'Data is not of expected type: `object`, but of type: `array`'],
        ];
    }

    /**
     * @return array[]
     */
    public function getClassNameTypeWithInvalidData(): array
    {
        // SIGNATURE: string $type, array $invalidData, string $message

        return [
            [TestEntity::class, [new TestEntity(), -1], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `integer`'],
            [TestEntity::class, [new TestEntity(), 0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `integer`'],
            [TestEntity::class, [new TestEntity(), 1], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `integer`'],
            [TestEntity::class, [new TestEntity(), true], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `boolean`'],
            [TestEntity::class, [new TestEntity(), false], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `boolean`'],
            [TestEntity::class, [new TestEntity(), -1.0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `float`'],
            [TestEntity::class, [new TestEntity(), 0.0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `float`'],
            [TestEntity::class, [new TestEntity(), 1.0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `float`'],
            [TestEntity::class, [new TestEntity(), 'text'], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `string`'],
            [TestEntity::class, [new TestEntity(), []], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `array`'],
            [TestEntity::class, [new TestEntity(), ['item1', 'item2']], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `array`'],
            [TestEntity::class, [new TestEntity(), ['key' => 'value']], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `array`'],
            [TestEntity::class, [new TestEntity(), new \stdClass()], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but an instance of: `stdClass`'],
            [TestEntity::class, [new TestEntityBase(), new \stdClass()], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            [TestEntityBase::class, [new TestEntity(), -1], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `integer`'],
            [TestEntityBase::class, [new TestEntity(), 0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `integer`'],
            [TestEntityBase::class, [new TestEntity(), 1], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `integer`'],
            [TestEntityBase::class, [new TestEntity(), true], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `boolean`'],
            [TestEntityBase::class, [new TestEntity(), false], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `boolean`'],
            [TestEntityBase::class, [new TestEntity(), -1.0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `float`'],
            [TestEntityBase::class, [new TestEntity(), 0.0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `float`'],
            [TestEntityBase::class, [new TestEntity(), 1.0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `float`'],
            [TestEntityBase::class, [new TestEntity(), 'text'], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `string`'],
            [TestEntityBase::class, [new TestEntity(), []], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `array`'],
            [TestEntityBase::class, [new TestEntity(), ['item1', 'item2']], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `array`'],
            [TestEntityBase::class, [new TestEntity(), ['key' => 'value']], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `array`'],
            [TestEntityBase::class, [new TestEntity(), new \stdClass()], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but an instance of: `stdClass`'],

            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), -1], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `integer`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), 0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `integer`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), 1], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `integer`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), true], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `boolean`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), false], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `boolean`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), -1.0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `float`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), 0.0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `float`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), 1.0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `float`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), 'text'], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `string`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), []], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `array`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), ['item1', 'item2']], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `array`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), ['key' => 'value']], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `array`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), new \stdClass()], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but an instance of: `stdClass`'],

            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), -1], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `integer`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), 0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `integer`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), 1], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `integer`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), true], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `boolean`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), false], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `boolean`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), -1.0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `float`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), 0.0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `float`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), 1.0], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `float`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), 'text'], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `string`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), []], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `array`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), ['item1', 'item2']], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `array`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), ['key' => 'value']], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `array`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), new \stdClass()], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but an instance of: `stdClass`']
        ];
    }
}