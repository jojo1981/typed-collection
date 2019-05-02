<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace tests\Jojo1981\TypedCollection\Metadata;

use Jojo1981\TypedCollection\Metadata\TypeMetadata;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use tests\Jojo1981\TypedCollection\Entity\TestEntity;
use tests\Jojo1981\TypedCollection\Entity\TestEntityBase;

/**
 * @package tests\Jojo1981\TypedCollection\Metadata
 */
class TypeMetadataTest extends TestCase
{
    /**
     * @test
     * @dataProvider getClassTypeTestData
     *
     * @param mixed $data
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isPrimitiveTypeShouldReturnFalse($data): void
    {
        $this->assertFalse((new TypeMetadata($data))->isPrimitiveType());
    }

    /**
     * @test
     * @dataProvider getPrimitiveTestData
     *
     * @param mixed $data
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isPrimitiveTypeShouldReturnTrue($data): void
    {
        $this->assertTrue((new TypeMetadata($data))->isPrimitiveType());
    }

    /**
     * @test
     * @dataProvider getPrimitiveTestData
     *
     * @param mixed $data
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isClassTypeShouldReturnFalse($data): void
    {
        $this->assertFalse((new TypeMetadata($data))->isClassType());
    }

    /**
     * @test
     * @dataProvider getClassTypeTestData
     *
     * @param mixed $data
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isClassTypeShouldReturnTrue($data): void
    {
        $this->assertTrue((new TypeMetadata($data))->isClassType());
    }

    /**
     * @test
     * @dataProvider getAllTestData
     *
     * @param mixed $data
     * @param string $expectedType
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function getTypeShouldReturnTheCorrectType($data, string $expectedType): void
    {
        $this->assertEquals($expectedType, (new TypeMetadata($data))->getType());
    }

    /**
     * @test
     * @dataProvider getAllTestData
     *
     * @param mixed $data
     * @param string $expectedType
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function toStringShouldReturnTheCorrectStringPresentation($data, string $expectedType): void
    {
        $this->assertEquals($expectedType, (string) new TypeMetadata($data));
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
    public function getAllTestData(): array
    {
        return \array_merge($this->getPrimitiveTestData(), $this->getClassTypeTestData());
    }
}