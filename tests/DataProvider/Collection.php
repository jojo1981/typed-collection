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
    public function getPrimitiveTypeWithInvalidData(): array
    {
        // SIGNATURE: string $type,array $invalidData, string $message

        return [
            ['string', ['text', 1], 'Data is not of expected type: `string`, but of type: `integer`']
        ];
    }

    /**
     * @return array[]
     */
    public function getClassNameTypeWithInvalidData(): array
    {
        // SIGNATURE: string $type,array $invalidData, string $message

        return [
            [TestEntity::class, [new TestEntity(), 'text'], 'Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `string`']
        ];
    }
}