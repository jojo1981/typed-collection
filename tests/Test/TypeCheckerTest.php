<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection\TestSuite\Test;

use Jojo1981\TypedCollection\TestSuite\Fixture\AbstractTestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase;
use Jojo1981\TypedCollection\TypeChecker;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @package Jojo1981\TypedCollection\TestSuite\Test
 */
class TypeCheckerTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\TypeChecker::getDataWithCorrectExpectedType
     *
     * @param mixed $data
     * @param string $expectedType
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function isDataOfExpectedTypeShouldReturnTrueWhenDataIsOfExpectedType($data, string $expectedType): void
    {
        $this->assertTrue(TypeChecker::isDataOfExpectedType($data, $expectedType));
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\TypeChecker::getDataWithInCorrectExpectedType
     *
     * @param mixed $data
     * @param string $expectedType
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function isDataOfExpectedTypeShouldReturnFalseWhenDataIsNotOfExpectedType($data, string $expectedType): void
    {
        $this->assertFalse(TypeChecker::isDataOfExpectedType($data, $expectedType));
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\TypeChecker::getCorrectTypeWithCorrectData
     *
     * @param string $expectedType
     * @param mixed $data
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function getTypeShouldReturnTheCorrectType(string $expectedType, $data): void
    {
        $this->assertEquals($expectedType, TypeChecker::getType($data));
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\TypeChecker::getPrimitiveTestData
     *
     * @param mixed $data
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function isClassTypeShouldReturnFalse($data): void
    {
        $this->assertFalse(TypeChecker::isClassType($data));
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\TypeChecker::getClassTypeTestData
     *
     * @param mixed $data
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function isClassTypeShouldReturnTrue($data): void
    {
        $this->assertTrue(TypeChecker::isClassType($data));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isInstanceOfShouldReturnTrue(): void
    {
        $this->assertTrue(TypeChecker::isInstanceOf(InterfaceTestEntity::class, InterfaceTestEntity::class));
        $this->assertTrue(TypeChecker::isInstanceOf(InterfaceTestEntity::class, AbstractTestEntity::class));
        $this->assertTrue(TypeChecker::isInstanceOf(InterfaceTestEntity::class, TestEntityBase::class));
        $this->assertTrue(TypeChecker::isInstanceOf(InterfaceTestEntity::class, TestEntity::class));

        $this->assertTrue(TypeChecker::isInstanceOf(AbstractTestEntity::class, AbstractTestEntity::class));
        $this->assertTrue(TypeChecker::isInstanceOf(AbstractTestEntity::class, TestEntityBase::class));
        $this->assertTrue(TypeChecker::isInstanceOf(AbstractTestEntity::class, TestEntity::class));

        $this->assertTrue(TypeChecker::isInstanceOf(TestEntityBase::class, TestEntityBase::class));
        $this->assertTrue(TypeChecker::isInstanceOf(TestEntityBase::class, TestEntity::class));

        $this->assertTrue(TypeChecker::isInstanceOf(TestEntity::class, TestEntity::class));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isInstanceOfShouldReturnFalse(): void
    {
        $this->assertFalse(TypeChecker::isInstanceOf(TestEntity::class, TestEntityBase::class));
        $this->assertFalse(TypeChecker::isInstanceOf(TestEntity::class, AbstractTestEntity::class));
        $this->assertFalse(TypeChecker::isInstanceOf(TestEntity::class, InterfaceTestEntity::class));

        $this->assertFalse(TypeChecker::isInstanceOf(TestEntityBase::class, AbstractTestEntity::class));
        $this->assertFalse(TypeChecker::isInstanceOf(TestEntityBase::class, InterfaceTestEntity::class));

        $this->assertFalse(TypeChecker::isInstanceOf(AbstractTestEntity::class, InterfaceTestEntity::class));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isExactlyTheSameClassTypeShouldReturnTrue(): void
    {
        $this->assertTrue(TypeChecker::isExactlyTheSameClassType(new TestEntity(), new TestEntity()));
        $this->assertTrue(TypeChecker::isExactlyTheSameClassType(new TestEntityBase(), new TestEntityBase()));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isExactlyTheSameClassTypeShouldReturnFalse(): void
    {
        $this->assertFalse(TypeChecker::isExactlyTheSameClassType(new TestEntity(), new TestEntityBase()));
        $this->assertFalse(TypeChecker::isExactlyTheSameClassType(new TestEntityBase(), new TestEntity()));
    }
}