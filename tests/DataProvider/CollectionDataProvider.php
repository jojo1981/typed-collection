<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection\TestSuite\DataProvider;

use Jojo1981\PhpTypes\Exception\TypeException;
use Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase;
use stdClass;

/**
 * @package Jojo1981\TypedCollection\TestSuite\DataProvider
 */
final class CollectionDataProvider
{
    /**
     * @return array[]
     */
    public function getInvalidTypes(): array
    {
        return [
            ['invalid-type', TypeException::class],
            ['mixed', null],
            ['null', null],
            ['void', null],
        ];
    }

    /**
     * @return array[]
     */
    public function getValidTypesMap(): array
    {
        // SIGNATURE: string $type, string $expectedType

        return [
            ['int', 'int'],
            ['integer', 'int'],
            ['bool', 'bool'],
            ['boolean', 'bool'],
            ['float', 'float'],
            ['real', 'float'],
            ['double', 'float'],
            ['number', 'float'],
            ['string', 'string'],
            ['text', 'string'],
            ['array', 'array'],
            ['object', 'object'],
            ['iterable', 'iterable'],
            ['callable', 'callable'],
            ['callback', 'callable'],
            [TestEntity::class , '\\' . TestEntity::class],
            [TestEntityBase::class, '\\' . TestEntityBase::class],
            [AbstractTestEntity::class, '\\' . AbstractTestEntity::class],
            [InterfaceTestEntity::class, '\\' . InterfaceTestEntity::class]
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
            ['string', 'text'],
            ['text', 'text'],
            ['text', 'string'],
            ['boolean', 'boolean'],
            ['bool', 'bool'],
            ['bool', 'boolean'],
            ['boolean', 'bool'],
            ['float', 'float'],
            ['float', 'double'],
            ['float', 'number'],
            ['float', 'real'],
            ['double', 'double'],
            ['double', 'number'],
            ['double', 'float'],
            ['double', 'real'],
            ['real', 'real'],
            ['real', 'double'],
            ['real', 'float'],
            ['real', 'number'],
            ['number', 'real'],
            ['number', 'number'],
            ['number', 'double'],
            ['number', 'float'],
            ['array', 'array'],
            ['object', 'object'],
            ['callable', 'callable'],
            ['callable', 'callback'],
            ['callback', 'callback'],
            ['callback', 'callable'],
            ['iterable', 'iterable'],
            ['resource', 'resource'],
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
            ['string', 'real'],
            ['string', 'array'],
            ['string', 'object'],
            ['string', 'callable'],
            ['string', 'callback'],
            ['string', 'iterable'],
            ['string', 'resource'],

            ['text', 'int'],
            ['text', 'integer'],
            ['text', 'bool'],
            ['text', 'boolean'],
            ['text', 'float'],
            ['text', 'double'],
            ['text', 'number'],
            ['text', 'real'],
            ['text', 'array'],
            ['text', 'object'],
            ['text', 'callable'],
            ['text', 'callback'],
            ['text', 'iterable'],
            ['text', 'resource'],

            ['int', 'string'],
            ['int', 'text'],
            ['int', 'bool'],
            ['int', 'boolean'],
            ['int', 'float'],
            ['int', 'double'],
            ['int', 'number'],
            ['int', 'real'],
            ['int', 'array'],
            ['int', 'object'],
            ['int', 'callable'],
            ['int', 'callback'],
            ['int', 'iterable'],
            ['int', 'resource'],

            ['integer', 'string'],
            ['integer', 'text'],
            ['integer', 'bool'],
            ['integer', 'boolean'],
            ['integer', 'float'],
            ['integer', 'double'],
            ['integer', 'number'],
            ['integer', 'real'],
            ['integer', 'array'],
            ['integer', 'object'],
            ['integer', 'callable'],
            ['integer', 'callback'],
            ['integer', 'iterable'],
            ['integer', 'resource'],

            ['bool', 'string'],
            ['bool', 'text'],
            ['bool', 'int'],
            ['bool', 'integer'],
            ['bool', 'float'],
            ['bool', 'double'],
            ['bool', 'number'],
            ['bool', 'real'],
            ['bool', 'array'],
            ['bool', 'object'],
            ['bool', 'callable'],
            ['bool', 'callback'],
            ['bool', 'iterable'],
            ['bool', 'resource'],

            ['boolean', 'string'],
            ['boolean', 'text'],
            ['boolean', 'int'],
            ['boolean', 'integer'],
            ['boolean', 'float'],
            ['boolean', 'double'],
            ['boolean', 'number'],
            ['boolean', 'real'],
            ['boolean', 'array'],
            ['boolean', 'object'],
            ['boolean', 'callable'],
            ['boolean', 'callback'],
            ['boolean', 'iterable'],
            ['boolean', 'resource'],

            ['float', 'string'],
            ['float', 'text'],
            ['float', 'int'],
            ['float', 'integer'],
            ['float', 'bool'],
            ['float', 'boolean'],
            ['float', 'array'],
            ['float', 'object'],
            ['float', 'callable'],
            ['float', 'callback'],
            ['float', 'iterable'],
            ['float', 'resource'],

            ['double', 'string'],
            ['double', 'text'],
            ['double', 'int'],
            ['double', 'integer'],
            ['double', 'bool'],
            ['double', 'boolean'],
            ['double', 'array'],
            ['double', 'object'],
            ['double', 'callable'],
            ['double', 'callback'],
            ['double', 'iterable'],
            ['double', 'resource'],

            ['real', 'string'],
            ['real', 'text'],
            ['real', 'int'],
            ['real', 'integer'],
            ['real', 'bool'],
            ['real', 'boolean'],
            ['real', 'array'],
            ['real', 'object'],
            ['real', 'callable'],
            ['real', 'iterable'],
            ['real', 'resource'],
            ['real', 'callable'],
            ['real', 'callback'],
            ['real', 'iterable'],
            ['real', 'resource'],

            ['number', 'string'],
            ['number', 'text'],
            ['number', 'int'],
            ['number', 'integer'],
            ['number', 'bool'],
            ['number', 'boolean'],
            ['number', 'array'],
            ['number', 'object'],
            ['number', 'callable'],
            ['number', 'iterable'],
            ['number', 'resource'],
            ['number', 'callable'],
            ['number', 'callback'],
            ['number', 'iterable'],
            ['number', 'resource'],

            ['array', 'string'],
            ['array', 'text'],
            ['array', 'int'],
            ['array', 'integer'],
            ['array', 'bool'],
            ['array', 'boolean'],
            ['array', 'float'],
            ['array', 'double'],
            ['array', 'number'],
            ['array', 'real'],
            ['array', 'object'],
            ['array', 'callable'],
            ['array', 'callback'],
            ['array', 'iterable'],
            ['array', 'resource'],

            ['object', 'string'],
            ['object', 'text'],
            ['object', 'int'],
            ['object', 'integer'],
            ['object', 'bool'],
            ['object', 'boolean'],
            ['object', 'float'],
            ['object', 'double'],
            ['object', 'number'],
            ['object', 'real'],
            ['object', 'array'],
            ['object', 'callable'],
            ['object', 'callback'],
            ['object', 'iterable'],
            ['object', 'resource'],

            ['callable', 'string'],
            ['callable', 'text'],
            ['callable', 'int'],
            ['callable', 'integer'],
            ['callable', 'bool'],
            ['callable', 'boolean'],
            ['callable', 'float'],
            ['callable', 'double'],
            ['callable', 'number'],
            ['callable', 'real'],
            ['callable', 'array'],
            ['callable', 'object'],
            ['callable', 'iterable'],
            ['callable', 'resource'],

            ['callback', 'string'],
            ['callback', 'text'],
            ['callback', 'int'],
            ['callback', 'integer'],
            ['callback', 'bool'],
            ['callback', 'boolean'],
            ['callback', 'float'],
            ['callback', 'double'],
            ['callback', 'number'],
            ['callback', 'real'],
            ['callback', 'array'],
            ['callback', 'object'],
            ['callback', 'iterable'],
            ['callback', 'resource'],

            ['iterable', 'string'],
            ['iterable', 'text'],
            ['iterable', 'int'],
            ['iterable', 'integer'],
            ['iterable', 'bool'],
            ['iterable', 'boolean'],
            ['iterable', 'float'],
            ['iterable', 'double'],
            ['iterable', 'number'],
            ['iterable', 'real'],
            ['iterable', 'array'],
            ['iterable', 'object'],
            ['iterable', 'callable'],
            ['iterable', 'callback'],
            ['iterable', 'resource'],

            ['resource', 'string'],
            ['resource', 'text'],
            ['resource', 'int'],
            ['resource', 'integer'],
            ['resource', 'bool'],
            ['resource', 'boolean'],
            ['resource', 'float'],
            ['resource', 'double'],
            ['resource', 'number'],
            ['resource', 'real'],
            ['resource', 'array'],
            ['resource', 'object'],
            ['resource', 'callable'],
            ['resource', 'callback'],
            ['resource', 'iterable'],

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

            ['int', [-1, 0, 1, 5, 'text'], 'Data is not of expected type: `int`, but of type: `string`'],
            ['int', [-1, 0, 1, 5, -1.0], 'Data is not of expected type: `int`, but of type: `float`'],
            ['int', [-1, 0, 1, 5, 0.0], 'Data is not of expected type: `int`, but of type: `float`'],
            ['int', [-1, 0, 1, 5, 1.0], 'Data is not of expected type: `int`, but of type: `float`'],
            ['int', [-1, 0, 1, 5, true], 'Data is not of expected type: `int`, but of type: `bool`'],
            ['int', [-1, 0, 1, 5, false], 'Data is not of expected type: `int`, but of type: `bool`'],
            ['int', [-1, 0, 1, 5, []], 'Data is not of expected type: `int`, but of type: `array`'],
            ['int', [-1, 0, 1, 5, ['item1', 'item2']], 'Data is not of expected type: `int`, but of type: `array`'],
            ['int', [-1, 0, 1, 5, ['key' => 'value']], 'Data is not of expected type: `int`, but of type: `array`'],
            ['int', [-1, 0, 1, 5, new stdClass()], 'Data is not of expected type: `int`, but an instance of: `\stdClass`'],
            ['int', [-1, 0, 1, 5, new TestEntity()], 'Data is not of expected type: `int`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['int', [-1, 0, 1, 5, new TestEntityBase()], 'Data is not of expected type: `int`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['integer', [-1, 0, 1, 5, 'text'], 'Data is not of expected type: `int`, but of type: `string`'],
            ['integer', [-1, 0, 1, 5, -1.0], 'Data is not of expected type: `int`, but of type: `float`'],
            ['integer', [-1, 0, 1, 5, 0.0], 'Data is not of expected type: `int`, but of type: `float`'],
            ['integer', [-1, 0, 1, 5, 1.0], 'Data is not of expected type: `int`, but of type: `float`'],
            ['integer', [-1, 0, 1, 5, true], 'Data is not of expected type: `int`, but of type: `bool`'],
            ['integer', [-1, 0, 1, 5, false], 'Data is not of expected type: `int`, but of type: `bool`'],
            ['integer', [-1, 0, 1, 5, []], 'Data is not of expected type: `int`, but of type: `array`'],
            ['integer', [-1, 0, 1, 5, ['item1', 'item2']], 'Data is not of expected type: `int`, but of type: `array`'],
            ['integer', [-1, 0, 1, 5, ['key' => 'value']], 'Data is not of expected type: `int`, but of type: `array`'],
            ['integer', [-1, 0, 1, 5, new stdClass()], 'Data is not of expected type: `int`, but an instance of: `\stdClass`'],
            ['integer', [-1, 0, 1, 5, new TestEntity()], 'Data is not of expected type: `int`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['integer', [-1, 0, 1, 5, new TestEntityBase()], 'Data is not of expected type: `int`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['float', [-1.0, 0.0, 1.0, 3.25, 'text'], 'Data is not of expected type: `float`, but of type: `string`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, -1], 'Data is not of expected type: `float`, but of type: `int`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, 0], 'Data is not of expected type: `float`, but of type: `int`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, 1], 'Data is not of expected type: `float`, but of type: `int`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, true], 'Data is not of expected type: `float`, but of type: `bool`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, false], 'Data is not of expected type: `float`, but of type: `bool`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, []], 'Data is not of expected type: `float`, but of type: `array`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, ['item1', 'item2']], 'Data is not of expected type: `float`, but of type: `array`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, ['key' => 'value']], 'Data is not of expected type: `float`, but of type: `array`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, new stdClass()], 'Data is not of expected type: `float`, but an instance of: `\stdClass`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, new TestEntity()], 'Data is not of expected type: `float`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['float', [-1.0, 0.0, 1.0, 3.25, new TestEntityBase()], 'Data is not of expected type: `float`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['double', [-1.0, 0.0, 1.0, 3.25, 'text'], 'Data is not of expected type: `float`, but of type: `string`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, -1], 'Data is not of expected type: `float`, but of type: `int`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, 0], 'Data is not of expected type: `float`, but of type: `int`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, 1], 'Data is not of expected type: `float`, but of type: `int`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, true], 'Data is not of expected type: `float`, but of type: `bool`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, false], 'Data is not of expected type: `float`, but of type: `bool`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, []], 'Data is not of expected type: `float`, but of type: `array`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, ['item1', 'item2']], 'Data is not of expected type: `float`, but of type: `array`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, ['key' => 'value']], 'Data is not of expected type: `float`, but of type: `array`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, new stdClass()], 'Data is not of expected type: `float`, but an instance of: `\stdClass`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, new TestEntity()], 'Data is not of expected type: `float`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['double', [-1.0, 0.0, 1.0, 3.25, new TestEntityBase()], 'Data is not of expected type: `float`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['number', [-1.0, 0.0, 1.0, 3.25, 'text'], 'Data is not of expected type: `float`, but of type: `string`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, -1], 'Data is not of expected type: `float`, but of type: `int`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, 0], 'Data is not of expected type: `float`, but of type: `int`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, 1], 'Data is not of expected type: `float`, but of type: `int`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, true], 'Data is not of expected type: `float`, but of type: `bool`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, false], 'Data is not of expected type: `float`, but of type: `bool`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, []], 'Data is not of expected type: `float`, but of type: `array`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, ['item1', 'item2']], 'Data is not of expected type: `float`, but of type: `array`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, ['key' => 'value']], 'Data is not of expected type: `float`, but of type: `array`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, new stdClass()], 'Data is not of expected type: `float`, but an instance of: `\stdClass`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, new TestEntity()], 'Data is not of expected type: `float`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['number', [-1.0, 0.0, 1.0, 3.25, new TestEntityBase()], 'Data is not of expected type: `float`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['string', ['text', -1], 'Data is not of expected type: `string`, but of type: `int`'],
            ['string', ['text', 0], 'Data is not of expected type: `string`, but of type: `int`'],
            ['string', ['text', 1], 'Data is not of expected type: `string`, but of type: `int`'],
            ['string', ['text', -1.0], 'Data is not of expected type: `string`, but of type: `float`'],
            ['string', ['text', 0.0], 'Data is not of expected type: `string`, but of type: `float`'],
            ['string', ['text', 1.0], 'Data is not of expected type: `string`, but of type: `float`'],
            ['string', ['text', true], 'Data is not of expected type: `string`, but of type: `bool`'],
            ['string', ['text', false], 'Data is not of expected type: `string`, but of type: `bool`'],
            ['string', ['text', []], 'Data is not of expected type: `string`, but of type: `array`'],
            ['string', ['text', ['item1', 'item2']], 'Data is not of expected type: `string`, but of type: `array`'],
            ['string', ['text', ['key' => 'value']], 'Data is not of expected type: `string`, but of type: `array`'],
            ['string', ['text', new stdClass()], 'Data is not of expected type: `string`, but an instance of: `\stdClass`'],
            ['string', ['text', new TestEntity()], 'Data is not of expected type: `string`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['string', ['text', new TestEntityBase()], 'Data is not of expected type: `string`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['boolean', [true, false, 'text'], 'Data is not of expected type: `bool`, but of type: `string`'],
            ['boolean', [true, false, -1], 'Data is not of expected type: `bool`, but of type: `int`'],
            ['boolean', [true, false, 0], 'Data is not of expected type: `bool`, but of type: `int`'],
            ['boolean', [true, false, 1], 'Data is not of expected type: `bool`, but of type: `int`'],
            ['boolean', [true, false, -1.0], 'Data is not of expected type: `bool`, but of type: `float`'],
            ['boolean', [true, false, 0.0], 'Data is not of expected type: `bool`, but of type: `float`'],
            ['boolean', [true, false, 1.0], 'Data is not of expected type: `bool`, but of type: `float`'],
            ['boolean', [true, false, []], 'Data is not of expected type: `bool`, but of type: `array`'],
            ['boolean', [true, false, ['item1', 'item2']], 'Data is not of expected type: `bool`, but of type: `array`'],
            ['boolean', [true, false, ['key' => 'value']], 'Data is not of expected type: `bool`, but of type: `array`'],
            ['boolean', [true, false, new stdClass()], 'Data is not of expected type: `bool`, but an instance of: `\stdClass`'],
            ['boolean', [true, false, new TestEntity()], 'Data is not of expected type: `bool`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['boolean', [true, false, new TestEntityBase()], 'Data is not of expected type: `bool`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],
            
            ['bool', [true, false, 'text'], 'Data is not of expected type: `bool`, but of type: `string`'],
            ['bool', [true, false, -1], 'Data is not of expected type: `bool`, but of type: `int`'],
            ['bool', [true, false, 0], 'Data is not of expected type: `bool`, but of type: `int`'],
            ['bool', [true, false, 1], 'Data is not of expected type: `bool`, but of type: `int`'],
            ['bool', [true, false, -1.0], 'Data is not of expected type: `bool`, but of type: `float`'],
            ['bool', [true, false, 0.0], 'Data is not of expected type: `bool`, but of type: `float`'],
            ['bool', [true, false, 1.0], 'Data is not of expected type: `bool`, but of type: `float`'],
            ['bool', [true, false, []], 'Data is not of expected type: `bool`, but of type: `array`'],
            ['bool', [true, false, ['item1', 'item2']], 'Data is not of expected type: `bool`, but of type: `array`'],
            ['bool', [true, false, ['key' => 'value']], 'Data is not of expected type: `bool`, but of type: `array`'],
            ['bool', [true, false, new stdClass()], 'Data is not of expected type: `bool`, but an instance of: `\stdClass`'],
            ['bool', [true, false, new TestEntity()], 'Data is not of expected type: `bool`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['bool', [true, false, new TestEntityBase()], 'Data is not of expected type: `bool`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['array', [[], ['item1', 'item2'], ['key' => 'value'], 'text'], 'Data is not of expected type: `array`, but of type: `string`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], -1], 'Data is not of expected type: `array`, but of type: `int`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], 0], 'Data is not of expected type: `array`, but of type: `int`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], 1], 'Data is not of expected type: `array`, but of type: `int`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], -1.0], 'Data is not of expected type: `array`, but of type: `float`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], 0.0], 'Data is not of expected type: `array`, but of type: `float`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], 1.0], 'Data is not of expected type: `array`, but of type: `float`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], true], 'Data is not of expected type: `array`, but of type: `bool`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], false], 'Data is not of expected type: `array`, but of type: `bool`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], new stdClass()], 'Data is not of expected type: `array`, but an instance of: `\stdClass`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], new TestEntity()], 'Data is not of expected type: `array`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`'],
            ['array', [[], ['item1', 'item2'], ['key' => 'value'], new TestEntityBase()], 'Data is not of expected type: `array`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            ['object', [new stdClass(), new TestEntity(), new TestEntityBase(), 'text'], 'Data is not of expected type: `object`, but of type: `string`'],
            ['object', [new stdClass(), new TestEntity(), new TestEntityBase(), -1], 'Data is not of expected type: `object`, but of type: `int`'],
            ['object', [new stdClass(), new TestEntity(), new TestEntityBase(), 0], 'Data is not of expected type: `object`, but of type: `int`'],
            ['object', [new stdClass(), new TestEntity(), new TestEntityBase(), 1], 'Data is not of expected type: `object`, but of type: `int`'],
            ['object', [new stdClass(), new TestEntity(), new TestEntityBase(), -1.0], 'Data is not of expected type: `object`, but of type: `float`'],
            ['object', [new stdClass(), new TestEntity(), new TestEntityBase(), 0.0], 'Data is not of expected type: `object`, but of type: `float`'],
            ['object', [new stdClass(), new TestEntity(), new TestEntityBase(), 1.0], 'Data is not of expected type: `object`, but of type: `float`'],
            ['object', [new stdClass(), new TestEntity(), new TestEntityBase(), true], 'Data is not of expected type: `object`, but of type: `bool`'],
            ['object', [new stdClass(), new TestEntity(), new TestEntityBase(), false], 'Data is not of expected type: `object`, but of type: `bool`'],
            ['object', [new stdClass(), new TestEntity(), new TestEntityBase(), []], 'Data is not of expected type: `object`, but of type: `array`'],
            ['object', [new stdClass(), new TestEntity(), new TestEntityBase(), ['item1', 'item2']], 'Data is not of expected type: `object`, but of type: `array`'],
            ['object', [new stdClass(), new TestEntity(), new TestEntityBase(), ['key' => 'value']], 'Data is not of expected type: `object`, but of type: `array`'],
        ];
    }

    /**
     * @return array[]
     */
    public function getClassNameTypeWithInvalidData(): array
    {
        // SIGNATURE: string $type, array $invalidData, string $message

        return [
            [TestEntity::class, [new TestEntity(), -1], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `int`'],
            [TestEntity::class, [new TestEntity(), 0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `int`'],
            [TestEntity::class, [new TestEntity(), 1], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `int`'],
            [TestEntity::class, [new TestEntity(), true], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `bool`'],
            [TestEntity::class, [new TestEntity(), false], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `bool`'],
            [TestEntity::class, [new TestEntity(), -1.0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `float`'],
            [TestEntity::class, [new TestEntity(), 0.0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `float`'],
            [TestEntity::class, [new TestEntity(), 1.0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `float`'],
            [TestEntity::class, [new TestEntity(), 'text'], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `string`'],
            [TestEntity::class, [new TestEntity(), []], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `array`'],
            [TestEntity::class, [new TestEntity(), ['item1', 'item2']], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `array`'],
            [TestEntity::class, [new TestEntity(), ['key' => 'value']], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `array`'],
            [TestEntity::class, [new TestEntity(), new stdClass()], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but an instance of: `\stdClass`'],
            [TestEntity::class, [new TestEntityBase(), new stdClass()], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`'],

            [TestEntityBase::class, [new TestEntity(), -1], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `int`'],
            [TestEntityBase::class, [new TestEntity(), 0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `int`'],
            [TestEntityBase::class, [new TestEntity(), 1], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `int`'],
            [TestEntityBase::class, [new TestEntity(), true], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `bool`'],
            [TestEntityBase::class, [new TestEntity(), false], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `bool`'],
            [TestEntityBase::class, [new TestEntity(), -1.0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `float`'],
            [TestEntityBase::class, [new TestEntity(), 0.0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `float`'],
            [TestEntityBase::class, [new TestEntity(), 1.0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `float`'],
            [TestEntityBase::class, [new TestEntity(), 'text'], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `string`'],
            [TestEntityBase::class, [new TestEntity(), []], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `array`'],
            [TestEntityBase::class, [new TestEntity(), ['item1', 'item2']], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `array`'],
            [TestEntityBase::class, [new TestEntity(), ['key' => 'value']], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but of type: `array`'],
            [TestEntityBase::class, [new TestEntity(), new stdClass()], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but an instance of: `\stdClass`'],

            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), -1], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `int`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), 0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `int`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), 1], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `int`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), true], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `bool`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), false], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `bool`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), -1.0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `float`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), 0.0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `float`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), 1.0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `float`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), 'text'], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `string`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), []], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `array`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), ['item1', 'item2']], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `array`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), ['key' => 'value']], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but of type: `array`'],
            [AbstractTestEntity::class, [new TestEntityBase(), new TestEntity(), new stdClass()], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity`, but an instance of: `\stdClass`'],

            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), -1], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `int`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), 0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `int`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), 1], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `int`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), true], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `bool`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), false], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `bool`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), -1.0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `float`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), 0.0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `float`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), 1.0], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `float`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), 'text'], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `string`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), []], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `array`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), ['item1', 'item2']], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `array`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), ['key' => 'value']], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but of type: `array`'],
            [InterfaceTestEntity::class, [new TestEntityBase(), new TestEntity(), new stdClass()], 'Data is not an instance of: `\Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity`, but an instance of: `\stdClass`']
        ];
    }
}
