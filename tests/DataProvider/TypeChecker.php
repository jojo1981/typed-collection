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
class TypeChecker
{
    /** @var string[] */
    private const TYPE_MAP = [
        'bool' => 'boolean',
        'int' => 'integer',
        'double' => 'float',
        'number' => 'float'
    ];

    /**
     * @param
     * @return array[]
     */
    public function getDataWithCorrectExpectedType(): array
    {
        // SIGNATURE: mixed $data, string $expectedType

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
     * @param
     * @return array[]
     */
    public function getDataWithInCorrectExpectedType(): array
    {
        // SIGNATURE: mixed $data, string $expectedType

        $result = [];
        foreach ($this->getTestTypes() as $expectedType) {
            foreach ($this->getInvalidDataForType($expectedType) as $invalidData) {
                $result[] = [$invalidData, $expectedType];
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getCorrectTypeWithCorrectData(): array
    {
        // SIGNATURE: string $expectedType, $data

        return [
            ['null', null],
            ['string', 'text'],
            ['string', ''],
            ['boolean', true],
            ['boolean', false],
            ['integer', -1],
            ['integer', 0],
            ['integer', 1],
            ['float', -1.0],
            ['float', 0.0],
            ['float', 1.0],
            ['float', 3.25],
            ['float', 3.25],
            ['array', []],
            ['array', ['item1', 'item2']],
            ['array', ['key1' => 'value1']],
            [\stdClass::class, new \stdClass()],
            [TestEntity::class, new TestEntity()],
            [TestEntityBase::class, new TestEntityBase()]
        ];
    }

    /**
     * @return array[]
     */
    public function getPrimitiveTestData(): array
    {
        // SIGNATURE: mixed $data

        return static::reverseElements([
            ['null', null],
            ['string', 'text'],
            ['string', ''],
            ['boolean', true],
            ['boolean', false],
            ['integer', -1],
            ['integer', 0],
            ['integer', 1],
            ['float', -1.0],
            ['float', 0.0],
            ['float', 1.0],
            ['float', 3.25],
            ['float', 3.25],
            ['array', []],
            ['array', ['item1', 'item2']],
            ['array', ['key1' => 'value1']],
        ]);
    }

    /**
     * @return array[]
     */
    public function getClassTypeTestData(): array
    {
        // SIGNATURE: mixed $data

        return static::reverseElements([
            [\stdClass::class, new \stdClass()],
            [TestEntity::class, new TestEntity()],
            [TestEntityBase::class, new TestEntityBase()]
        ]);
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
            InterfaceTestEntity::class => [1, 3.25, false, 'text', [], new \stdClass()],
            AbstractTestEntity::class => [1, 3.25, false, 'text', [], new \stdClass()],
            TestEntityBase::class => [1, 3.25, false, 'text', [], new \stdClass()],
            TestEntity::class => [1, 3.25, false, 'text', [], new \stdClass(), new TestEntityBase()],
        ];

        return $data[$this->mapType($type)];
    }

    /**
     * @return string[]
     */
    private function getTestTypes(): array
    {
        return [
            'int',
            'integer',
            'number',
            'double',
            'float',
            'bool',
            'boolean',
            'string',
            'array',
            'object',
            InterfaceTestEntity::class,
            AbstractTestEntity::class,
            TestEntityBase::class,
            TestEntity::class
        ];
    }

    /**
     * @param string $type
     * @return string
     */
    private function mapType(string $type): string
    {
        if (\array_key_exists($type, static::TYPE_MAP)) {
            return static::TYPE_MAP[$type];
        }

        return $type;
    }

    /**
     * @param array $data
     * @return array
     */
    public static function reverseElements(array $data): array
    {
        return \array_map('\array_reverse', $data);
    }
}