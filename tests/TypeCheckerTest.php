<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace tests\Jojo1981\TypedCollection;

use Jojo1981\TypedCollection\TypeChecker;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use tests\Jojo1981\TypedCollection\Entity\AbstractTestEntity;
use tests\Jojo1981\TypedCollection\Entity\InterfaceTestEntity;
use tests\Jojo1981\TypedCollection\Entity\TestEntity;
use tests\Jojo1981\TypedCollection\Entity\TestEntityBase;

/**
 * @package tests\Jojo1981\TypedCollection
 */
class TypeCheckerTest extends TestCase
{
    /**
     * @test
     * @dataProvider getDataWithCorrectExpectedType
     *
     * @param mixed $data
     * @param string $expectedType
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isDataOfExpectedTypeShouldReturnTrueWhenDataIsOfExpectedType($data, string $expectedType): void
    {
        $this->assertTrue(TypeChecker::isDataOfExpectedType($data, $expectedType));
    }

    /**
     * @test
     * @dataProvider getDataWithInCorrectExpectedType
     *
     * @param mixed $data
     * @param string $expectedType
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isDataOfExpectedTypeShouldReturnFalseWhenDataIsNotOfExpectedType($data, string $expectedType): void
    {
        $this->assertFalse(TypeChecker::isDataOfExpectedType($data, $expectedType));
    }

    /**
     * @test
     * @dataProvider getAllValidTestData
     *
     * @param mixed $data
     * @param string $expectedType
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function getTypeShouldReturnTheCorrectType($data, string $expectedType): void
    {
        $this->assertEquals($expectedType, TypeChecker::getType($data));
    }

    /**
     * @test
     * @dataProvider getPrimitiveTestData
     *
     * @param mixed $data
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function isClassTypeShouldReturnFalse($data): void
    {
        $this->assertFalse(TypeChecker::isClassType($data));
    }

    /**
     * @test
     * @dataProvider getClassTypeTestData
     *
     * @param mixed $data
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function isClassTypeShouldReturnTrue($data): void
    {
        $this->assertTrue(TypeChecker::isClassType($data));
    }

    /**
     * @return array[]
     */
    public function getDataWithCorrectExpectedType(): array
    {
        return [
            [null, 'null'],
            ['text', 'string'],
            ['', 'string'],
            [true, 'boolean'],
            [false, 'boolean'],
            [true, 'bool'],
            [false, 'bool'],
            [-1, 'integer'],
            [0, 'integer'],
            [1, 'integer'],
            [-1, 'int'],
            [0, 'int'],
            [1, 'int'],
            [-1.0, 'float'],
            [0.0, 'float'],
            [1.0, 'float'],
            [3.25, 'float'],
            [3.25, 'float'],
            [-1.0, 'number'],
            [0.0, 'number'],
            [1.0, 'number'],
            [3.25, 'number'],
            [3.25, 'number'],
            [-1.0, 'double'],
            [0.0, 'double'],
            [1.0, 'Double'],
            [3.25, 'double'],
            [3.25, 'double'],
            [[], 'array'],
            [['item1', 'item2'], 'array'],
            [['key1' => 'value1'], 'array'],
            [new \stdClass(), 'object'],
            [new TestEntity(), 'object'],
            [new TestEntityBase(), 'object'],
            [new \stdClass(), \stdClass::class],
            [new TestEntity(), TestEntity::class],
            [new TestEntityBase(), TestEntityBase::class],
            [new TestEntity(), TestEntityBase::class],
            [new TestEntity(), AbstractTestEntity::class],
            [new TestEntity(), InterfaceTestEntity::class]
        ];
    }

    /**
     * @return array[]
     */
    public function getPrimitiveTestData(): array
    {
        return [
            [null, 'null'],
            ['text', 'string'],
            ['', 'string'],
            [true, 'boolean'],
            [false, 'boolean'],
            [-1, 'integer'],
            [0, 'integer'],
            [1, 'integer'],
            [-1.0, 'float'],
            [0.0, 'float'],
            [1.0, 'float'],
            [3.25, 'float'],
            [3.25, 'float'],
            [[], 'array'],
            [['item1', 'item2'], 'array'],
            [['key1' => 'value1'], 'array'],
        ];
    }

    /**
     * @return array[]
     */
    public function getClassTypeTestData(): array
    {
        return [
            [new \stdClass(), \stdClass::class],
            [new TestEntity(), TestEntity::class],
            [new TestEntityBase(), TestEntityBase::class]
        ];
    }

    /**
     * @return array[]
     */
    public function getAllValidTestData(): array
    {
        return \array_merge($this->getPrimitiveTestData(), $this->getClassTypeTestData());
    }

    /**
     * @return array[]
     */
    public function getDataWithInCorrectExpectedType(): array
    {
        $result = [];
        foreach ($this->getTestTypes() as $expectedType) {
            foreach ($this->getInvalidDataForType($expectedType) as $invalidData) {
                $result[] = [$invalidData, $expectedType];
            }
        }

        return $result;
    }

    /**
     * @param string $type
     * @return mixed[]
     */
    private function getInvalidDataForType(string $type): array
    {
        $data = [
            'integer' => [3.25, false, 'text', [], new \stdClass(), new TestEntity(), new TestEntityBase()],
            'float' => [1, false, 'text', [], new \stdClass(), new TestEntity(), new TestEntityBase()],
            'boolean' => [1, 3.25, 'text', [], new \stdClass(), new TestEntity(), new TestEntityBase()],
            'string' => [1, 3.25, false, [], new \stdClass(), new TestEntity(), new TestEntityBase()],
            'array' => [1, 3.25, false, 'text', new \stdClass(), new TestEntity(), new TestEntityBase()],
            'object' => [1, 3.25, false, 'text', []],
            TestEntityBase::class => [1, 3.25, false, 'text', [], new \stdClass()],
            TestEntity::class => [1, 3.25, false, 'text', [], new \stdClass(), new TestEntityBase()]
        ];

        return $data[$type];
    }

    /**
     * @return string[]
     */
    private function getTestTypes(): array
    {
        return [
            'integer',
            'float',
            'boolean',
            'string',
            'array',
            'object',
            TestEntityBase::class,
            TestEntity::class
        ];
    }
}