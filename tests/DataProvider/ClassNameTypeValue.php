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
use Jojo1981\TypedCollection\Value\Exception\ValueException;
use Jojo1981\TypedCollection\Value\Validation\ErrorValidationResult;
use Jojo1981\TypedCollection\Value\Validation\SuccessValidationResult;

/**
 * @package Jojo1981\TypedCollection\TestSuite\DataProvider
 */
class ClassNameTypeValue
{
    /**
     * @return array[]
     */
    public function getInvalidValueStrings(): array
    {
        // SIGNATURE: string $value, string $message

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
        // SIGNATURE: string $value, string $mappedValue

        return [
            [InterfaceTestEntity::class, InterfaceTestEntity::class],
            [AbstractTestEntity::class, AbstractTestEntity::class],
            [TestEntityBase::class, TestEntityBase::class],
            [TestEntity::class, TestEntity::class],
            ['\\' . InterfaceTestEntity::class, InterfaceTestEntity::class],
            ['\\' . AbstractTestEntity::class, AbstractTestEntity::class],
            ['\\' . TestEntityBase::class, TestEntityBase::class],
            ['\\' . TestEntity::class, TestEntity::class]
        ];
    }

    /**
     * @throws ValueException
     * @return array[]
     */
    public function getIsValidDataTestData(): array
    {
        // SIGNATURE: string $value, mixed $invalidData, ValidationResultInterface $expectValidationResult

        return [
            [TestEntityBase::class, new TestEntity(), new SuccessValidationResult()],
            [TestEntity::class, new TestEntity(), new SuccessValidationResult()],
            ['\\' . TestEntity::class, new TestEntity(), new SuccessValidationResult()],
            [TestEntityBase::class, new TestEntityBase(), new SuccessValidationResult()],
            ['\\' . TestEntityBase::class, new TestEntityBase(), new SuccessValidationResult()],
            [\stdClass::class, new \stdClass(), new SuccessValidationResult()],
            [TestEntity::class, new TestEntityBase(), new ErrorValidationResult('Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`')],
            [TestEntity::class, new \stdClass(), new ErrorValidationResult('Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but an instance of: `stdClass`')],
            [TestEntityBase::class, new \stdClass(), new ErrorValidationResult('Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`, but an instance of: `stdClass`')],
            [\stdClass::class, new TestEntityBase(), new ErrorValidationResult('Data is not an instance of: `stdClass`, but an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase`')],
            [\stdClass::class, [], new ErrorValidationResult('Data is not an instance of: `stdClass`, but of type: `array`')],
            [\stdClass::class, 'text', new ErrorValidationResult('Data is not an instance of: `stdClass`, but of type: `string`')],
            [\stdClass::class, -10, new ErrorValidationResult('Data is not an instance of: `stdClass`, but of type: `integer`')],
            [\stdClass::class, false, new ErrorValidationResult('Data is not an instance of: `stdClass`, but of type: `boolean`')],
            [\stdClass::class, true, new ErrorValidationResult('Data is not an instance of: `stdClass`, but of type: `boolean`')],
            [TestEntity::class, 0.0, new ErrorValidationResult('Data is not an instance of: `Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity`, but of type: `float`')]
        ];
    }
}