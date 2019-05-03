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
use Jojo1981\TypedCollection\Value\Type\AbstractTypeValue;
use Jojo1981\TypedCollection\Value\Type\ClassNameTypeValue;
use Jojo1981\TypedCollection\Value\Type\PrimitiveTypeValue;
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
class AbstractTypeValueTest extends TestCase
{
    /**
     * @test
     * @dataProvider getInvalidTypeStrings
     *
     * @param string $value
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isValidValueShouldReturnFalseWhenValueIsNotValid(string $value): void
    {
        $this->assertFalse(AbstractTypeValue::isValidValue($value));
    }

    /**
     * @test
     * @dataProvider getValidTypeStrings
     *
     * @param string $value
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function isValidValueShouldReturnTrueWhenValueIsValid(string $value): void
    {
        $this->assertTrue(AbstractTypeValue::isValidValue($value));
    }

    /**
     * @test
     * @dataProvider getInvalidTypeStrings
     *
     * @param string $value
     * @param string $message
     * @throws ValueException
     * @return void
     */
    public function createTypeValueInstanceShouldThrowValueExceptionWhenInvalidTypeIsGiven(
        string $value,
        string $message
    ): void
    {
        $this->expectExceptionObject(new ValueException($message));
        AbstractTypeValue::createTypeValueInstance($value);
    }

    /**
     * @test
     * @dataProvider getValidTypeStrings
     *
     * @param string $value
     * @param string $expectedInstanceOf
     * @throws ValueException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function createTypeValueInstanceShouldReturnTheCorrectValueTypeObjectInstanceBecauseGivenValueIsValid(
        string $value,
        string $expectedInstanceOf
    ): void
    {
        $this->assertInstanceOf($expectedInstanceOf, AbstractTypeValue::createTypeValueInstance($value));
    }

    /**
     * @return array[]
     */
    public function getInvalidTypeStrings(): array
    {
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