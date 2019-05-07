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

use Jojo1981\TypedCollection\Collection;
use Jojo1981\TypedCollection\Exception\CollectionException;
use Jojo1981\TypedCollection\TestSuite\Fixture\InterfaceTestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntity;
use Jojo1981\TypedCollection\TestSuite\Fixture\TestEntityBase;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @package Jojo1981\TypedCollection\TestSuite\Test
 */
class CollectionTest extends TestCase
{
    /**
     * @test
     *
     * @throws CollectionException
     * @return void
     */
    public function constructWithInvalidTypeShouldThrowCollectionException(): void
    {
        $this->expectExceptionObject(new CollectionException(
            'Given type: `invalid-type` is not a valid primitive type and also not an existing class'
        ));
        new Collection('invalid-type');
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\Collection::getPrimitiveTypeWithInvalidData
     *
     * @param string $type
     * @param mixed[] $invalidData
     * @param string $message
     * @throws CollectionException
     * @return void
     */
    public function constructWithValidPrimitiveTypeButInvalidElementShouldThrowCollectionException(
        string $type,
        array $invalidData,
        string $message
    ): void
    {
        $this->expectExceptionObject(new CollectionException($message));
        new Collection($type, $invalidData);
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\Collection::getClassNameTypeWithInvalidData
     *
     * @param string $type
     * @param mixed[] $invalidData
     * @param string $message
     * @throws CollectionException
     * @return void
     */
    public function constructWithValidClassNameTypeButInvalidElementShouldThrowCollectionException(
        string $type,
        array $invalidData,
        string $message
    ): void
    {
        $this->expectExceptionObject(new CollectionException($message));
        new Collection($type, $invalidData);
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\Collection::getValidTypesMap
     *
     * @param string $type
     * @param string $expectedType
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function getTypeShouldReturnTheCorrectType(string $type, string $expectedType): void
    {
        $this->assertEquals($expectedType, (new Collection($type))->getType());
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @return void
     */
    public function createFromCollectionsShouldThrowCollectionExceptionBecauseInvalidTypeIsGiven(): void
    {
        $this->expectExceptionObject(new CollectionException(
            'Given type: `invalidType` is not a valid primitive type and also not an existing class'
        ));
        Collection::createFromCollections('invalidType', []);
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @return void
     */
    public function createFromCollectionsShouldThrowCollectionExceptionBecauseEmptyArrayGiven(): void
    {
        $this->expectExceptionObject(new CollectionException('An empty array with typed collections passed'));
        Collection::createFromCollections('string', []);
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @return void
     */
    public function createFromCollectionsShouldThrowCollectionExceptionBecauseNotEnoughCollectionsGiven(): void
    {
        $this->expectExceptionObject(new CollectionException('At least 2 collections needs to be passed'));
        Collection::createFromCollections('string', [new Collection('string')]);
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @return void
     */
    public function createFromCollectionsShouldThrowCollectionExceptionBecauseArgumentCollectionsDoesNotContainOnlyCollection(): void
    {
        $this->expectExceptionObject(new CollectionException('Expect $collections array to contain instances of Collection'));
        Collection::createFromCollections('string', [new Collection('string'), 'text']);
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @return void
     */
    public function createFromCollectionsShouldThrowCollectionExceptionBecauseNotAllCollectionAreOfTheExpectedType(): void
    {
        $this->expectExceptionObject(new CollectionException(
            'Expect every collection to be of type: `string`. Collection found with type: `integer`'
        ));
        Collection::createFromCollections('string', [new Collection('string'), new Collection('int')]);
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function createFromCollectionsShouldReturnOneCollection(): void
    {
        $element1 = new TestEntity();
        $element2 = new TestEntity();
        $element3 = new TestEntity();

        $element4 = new TestEntity();
        $element5 = new TestEntityBase();
        $element6 = new TestEntityBase();

        $collection1 = new Collection(TestEntity::class, [$element1, $element2, $element3]);
        $collection2 = new Collection(TestEntityBase::class, [$element4, $element5, $element6]);

        $expectedResult = new Collection(
            InterfaceTestEntity::class,
            [$element1, $element2, $element3, $element4, $element5, $element6]
        );
        $actualResult = Collection::createFromCollections(
            InterfaceTestEntity::class,
            [$collection1, $collection2]
        );

        $this->assertEquals($expectedResult, $actualResult);
    }
}