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
use Jojo1981\TypedCollection\Value\Type\ClassNameTypeValue;
use Jojo1981\TypedCollection\Value\Type\PrimitiveTypeValue;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use tests\Jojo1981\TypedCollection\Entity\TestEntity;
use tests\Jojo1981\TypedCollection\Entity\TestEntityBase;

/**
 * @package tests\Jojo1981\TypedCollection\Value\Type
 */
class PrimitiveTypeValueTest extends TestCase
{
    /**
     * @test
     * @dataProvider getInvalidValueStrings
     *
     * @param string $value
     * @return void
     *@throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function isValidValueShouldReturnFalseForInvalidValue(string $value): void
    {
        $this->assertFalse(PrimitiveTypeValue::isValidValue($value));
    }

    /**
     * @test
     * @dataProvider getInvalidValueStrings
     *
     * @param string $value
     * @param string $message
     * @return void
     *@throws ValueException
     */
    public function constructWithInvalidTypeShouldThrowValueException(string $value, string $message): void
    {
        $this->expectExceptionObject(new ValueException($message));
        new PrimitiveTypeValue($value);
    }

    /**
     * @test
     * @dataProvider getValidValueStrings
     *
     * @param string $value
     * @return void
     *@throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function isValidValueShouldReturnTrueForValidValue(string $value): void
    {
        $this->assertTrue(PrimitiveTypeValue::isValidValue($value));
    }

    /**
     * @test
     * @dataProvider getValidValueStrings
     *
     * @param string $value
     * @param string $mappedValue
     * @return void
     *@throws ValueException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function constructWithValidValueShouldReturnCorrectMappedValue(string $value, string $mappedValue): void
    {
        $classNameTypeValue = new PrimitiveTypeValue($value);
        $this->assertEquals($mappedValue, $classNameTypeValue->getValue());
        $this->assertEquals($mappedValue, (string) $classNameTypeValue);
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function matchShouldReturnFalseWhenTypeValueObjectAreNotMatching(): void
    {
        $primitiveTypeValue1 = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_INT);
        $primitiveTypeValue2 = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_STRING);

        $classNameTypeValue = new ClassNameTypeValue(TestEntity::class);

        $this->assertFalse($primitiveTypeValue1->match($primitiveTypeValue2));
        $this->assertFalse($primitiveTypeValue2->match($primitiveTypeValue1));

        $this->assertFalse($primitiveTypeValue1->match($classNameTypeValue));
        $this->assertFalse($primitiveTypeValue2->match($classNameTypeValue));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function matchShouldReturnTrueWhenTypeValueObjectAreMatching(): void
    {
        $primitiveTypeValue1 = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_INT);
        $primitiveTypeValue2 = new PrimitiveTypeValue(PrimitiveTypeValue::VALUE_INTEGER);

        $this->assertTrue($primitiveTypeValue1->match($primitiveTypeValue1));
        $this->assertTrue($primitiveTypeValue2->match($primitiveTypeValue2));

        $this->assertTrue($primitiveTypeValue1->match($primitiveTypeValue2));
        $this->assertTrue($primitiveTypeValue2->match($primitiveTypeValue1));
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function getValidValuesShouldReturnAllValidValues(): void
    {
        $this->assertEquals(
            ['int', 'integer', 'float', 'double', 'number', 'bool', 'boolean', 'string', 'array', 'object'],
            PrimitiveTypeValue::getValidValues()
        );
    }

    /**
     * @test
     * @dataProvider getInvalidDataMap
     *
     * @param string $value
     * @param $invalidDataValue
     * @throws ValueException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @return void
     */
    public function isValidDataShouldReturnFalseWhenDataIsNotConformThePrimitiveType(
        string $value,
        $invalidDataValue
    ): void
    {
        $this->assertFalse((new PrimitiveTypeValue($value))->isValidData($invalidDataValue));
    }

    /**
     * @test
     * @dataProvider getValidDataMap
     *
     * @param string $value
     * @param $validDataValue
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ValueException
     * @return void
     */
    public function isValidDataShouldReturnTrueWhenDataIsConformThePrimitiveType(
        string $value,
        $validDataValue
    ): void
    {
        $this->assertTrue((new PrimitiveTypeValue($value))->isValidData($validDataValue));
    }

    /**
     * @return array[]
     */
    public function getInvalidDataMap(): array
    {
        $result = [];
        foreach ($this->getValidTypes() as $type) {
            foreach ($this->getInvalidDataForType($type) as $invalidDataValue) {
                $result[] = [$type, $invalidDataValue];
            }
        }

        return $result;
    }

    /**
     * @return array[]
     */
    public function getValidDataMap(): array
    {
        $result = [];
        foreach ($this->getValidTypes() as $type) {
            foreach ($this->getValidDataForType($type) as $validDataValue) {
                $result[] = [$type, $validDataValue];
            }
        }

        return $result;
    }

    /**
     * @return array[]
     */
    public function getValidValueStrings(): array
    {
        return [
            ['int', 'integer'],
            ['integer', 'integer'],
            ['float', 'float'],
            ['double', 'float'],
            ['number', 'float'],
            ['bool', 'boolean'],
            ['boolean', 'boolean'],
            ['string', 'string'],
            ['array', 'array'],
            ['object', 'object'],
            ['INT', 'integer'],
            ['INTEGER', 'integer'],
            ['FLOAT', 'float'],
            ['DOUBLE', 'float'],
            ['NUMBER', 'float'],
            ['BOOL', 'boolean'],
            ['BOOLEAN', 'boolean'],
            ['STRING', 'string'],
            ['ARRAY', 'array'],
            ['OBJECT', 'object']
        ];
    }

    /**
     * @return array[]
     */
    public function getInvalidValueStrings(): array
    {
        $suffix = ' Valid types are [int, integer, float, double, number, bool, boolean, string, array, object]';

        return [
            [
                'invalid-type-value',
                'Invalid type: `invalid-type-value` given.' . $suffix
            ],
            [
                TestEntity::class,
                'Invalid type: `' . TestEntity::class . '` given.' . $suffix
            ],
            [
                \stdClass::class,
                'Invalid type: `' . \stdClass::class . '` given.' . $suffix
            ]
        ];
    }

    /**
     * @return array
     */
    private function getValidTypes(): array
    {
        return [
            'int',
            'integer',
            'float',
            'double',
            'number',
            'bool',
            'boolean',
            'string',
            'array',
            'object'
        ];
    }

    /**
     * @param string $type
     * @return array
     */
    private function getValidDataForType(string $type): array
    {
        $data = [
            'integer' => [0, 1, 10, -1, -10],
            'float' => [0.0, -1.0, 1.0, 2.75, -5.5],
            'boolean' => [true, false],
            'string' => ['text1', 'text2', 'text3', TestEntity::class],
            'array' => [[], ['item1', 'item2'], ['key' =>' value']],
            'object' => [new \stdClass(), new TestEntity(), new TestEntityBase()],
        ];

        return $data[$this->mapType($type)];
    }

    /**
     * @param string $type
     * @return array
     */
    private function getInvalidDataForType(string $type): array
    {
        $data = [
            'integer' => [3.47, -5.23, 5.0, -5.0, 'text', true, false, [], ['item1', 'item2'], ['key' =>' value'], new \stdClass(), new TestEntity()],
            'float' => [0, 1, 10, -3, 'text', true, false, [], ['item1', 'item2'], ['key' =>' value'], new \stdClass(), new TestEntity()],
            'boolean' => [0, 1, 10, 3.47, -3, -5.23, 5.0, -5.0, 'text', [], ['item1', 'item2'], ['key' =>' value'], new \stdClass(), new TestEntity()],
            'string' => [0, 1, 10, 3.47, -3, -5.23, 5.0, -5.0, true, false, [], ['item1', 'item2'], ['key' =>' value'], new \stdClass(), new TestEntity()],
            'array' => [0, 1, 10, 3.47, -3, -5.23, 5.0, -5.0, 'text', true, false, new \stdClass(), new TestEntity()],
            'object' => [0, 1, 10, 3.47, -3, -5.23, 5.0, -5.0, 'text', true, false, [], ['item1', 'item2'], ['key' =>' value']],
        ];

        return $data[$this->mapType($type)];
    }

    /**
     * @param string $type
     * @return string
     */
    private function mapType(string $type): string
    {
        $map = [
            'int' => 'integer',
            'double' => 'float',
            'number' => 'float',
            'bool' => 'boolean'
        ];

        if (\array_key_exists($type, $map)) {
            return $map[$type];
        }

        return $type;
    }
}