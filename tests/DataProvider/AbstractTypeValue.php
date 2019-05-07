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
use Jojo1981\TypedCollection\Value\Type\ClassNameTypeValue;
use Jojo1981\TypedCollection\Value\Type\PrimitiveTypeValue;

/**
 * @package Jojo1981\TypedCollection\TestSuite\DataProvider
 */
class AbstractTypeValue
{
    /**
     * @return array[]
     */
    public function getInvalidTypeStrings(): array
    {
        // SIGNATURE: string $value, string $message

        return [
            ['invalid-type-value', 'Could not create a type value instance based on value: `invalid-type-value`'],
            // existing namespace but no a class or interface
            ['tests\Jojo1981\TypedCollection', 'Could not create a type value instance based on value: `tests\Jojo1981\TypedCollection`'],
            ['Non\Existing\Class', 'Could not create a type value instance based on value: `Non\Existing\Class`']
        ];
    }

    /**
     * @return array[]
     */
    public function getValidTypeStrings(): array
    {
        // SIGNATURE: string $value, string $expectedInstanceOf

        return [
            ['int', PrimitiveTypeValue::class],
            ['integer', PrimitiveTypeValue::class],
            ['float', PrimitiveTypeValue::class],
            ['double', PrimitiveTypeValue::class],
            ['number', PrimitiveTypeValue::class],
            ['bool', PrimitiveTypeValue::class],
            ['boolean', PrimitiveTypeValue::class],
            ['string', PrimitiveTypeValue::class],
            ['array', PrimitiveTypeValue::class],
            ['object', PrimitiveTypeValue::class],
            [\stdClass::class, ClassNameTypeValue::class],
            ['\stdClass', ClassNameTypeValue::class],
            [TestEntityBase::class, ClassNameTypeValue::class],
            [TestEntity::class, ClassNameTypeValue::class],
            [InterfaceTestEntity::class, ClassNameTypeValue::class],
            [AbstractTestEntity::class, ClassNameTypeValue::class]
        ];
    }
}