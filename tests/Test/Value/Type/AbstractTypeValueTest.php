<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection\TestSuite\Test\Value\Type;

use Jojo1981\TypedCollection\Value\Exception\ValueException;
use Jojo1981\TypedCollection\Value\Type\AbstractTypeValue;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @package Jojo1981\TypedCollection\TestSuite\Test\Value\Type
 */
class AbstractTypeValueTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\AbstractTypeValue::getInvalidTypeStrings
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
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\AbstractTypeValue::getValidTypeStrings
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
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\AbstractTypeValue::getInvalidTypeStrings
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
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\AbstractTypeValue::getValidTypeStrings
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
}