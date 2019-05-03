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
use tests\Jojo1981\TypedCollection\Entity\AbstractTestEntity;
use tests\Jojo1981\TypedCollection\Entity\InterfaceTestEntity;
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
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
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
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
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
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
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
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function isClassTypeShouldReturnTrue($data): void
    {
        $this->assertTrue((new TypeMetadata($data))->isClassType());
    }

    /**
     * @test
     * @dataProvider getAllValidTestData
     *
     * @param mixed $data
     * @param string $expectedType
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function getTypeShouldReturnTheCorrectType($data, string $expectedType): void
    {
        $this->assertEquals($expectedType, (new TypeMetadata($data))->getType());
    }

    /**
     * @test
     * @dataProvider getAllValidTestData
     *
     * @param mixed $data
     * @param string $expectedType
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function toStringShouldReturnTheCorrectStringPresentation($data, string $expectedType): void
    {
        $this->assertEquals($expectedType, (string)new TypeMetadata($data));
    }

    /**
     * @test
     * @dataProvider getValidDataForMatchType
     *
     * @param mixed $data
     * @param string $typeToTest
     * @return void
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public function matchTypeShouldReturnTrueWhenTypeMatches($data, string $typeToTest): void
    {
        $this->assertTrue((new TypeMetadata($data))->matchType($typeToTest));
    }

    /**
     * @test
     * @dataProvider getInvalidDataForMatchType
     *
     * @param mixed $data
     * @param string $typeToTest
     * @return void
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     */
    public function matchTypeShouldReturnTFalseWhenTypeNotMatches($data, string $typeToTest): void
    {
        $this->assertFalse((new TypeMetadata($data))->matchType($typeToTest));
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
    public function getValidDataForMatchType(): array
    {
        return \array_merge(
            $this->getAllValidTestData(),
            [
                [new \stdClass(), 'object'],
                [new TestEntity(), 'object'],
                [new TestEntityBase(), 'object'],
                [new \stdClass(), \stdClass::class],
                [new TestEntity(), TestEntity::class],
                [new TestEntityBase(), TestEntityBase::class],
                [new TestEntity(), TestEntityBase::class], // inheritance
                [new TestEntity(), AbstractTestEntity::class], // inheritance
                [new TestEntity(), InterfaceTestEntity::class], // inheritance
            ]
        );
    }

    /**
     * @return array[]
     */
    public function getInvalidDataForMatchType(): array
    {
        $result = [];
        foreach ($this->getPrimitiveTestData() as [$data, $matchingType]) {
            foreach ($this->getAllTypesExcept($matchingType) as $notMatchingType) {
                $result[] = [$data, $notMatchingType];
            }
        }

        $result[] = [new TestEntityBase(), TestEntity::class]; // does not inherit
        $result[] = [new TestEntityBase(), \stdClass::class];

        return $result;
    }

    /**
     * @param string $excludedType
     * @return string[]
     */
    private function getAllTypesExcept(string $excludedType): array
    {
        $types = [
            'integer',
            'float',
            'boolean',
            'string',
            'array',
            'object',
            \stdClass::class,
            TestEntity::class,
            TestEntityBase::class
        ];

        return \array_filter(
            $types,
            static function (string $type) use ($excludedType): bool {
                return $type !== $excludedType;
            }
        );
    }
}