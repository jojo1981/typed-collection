<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace tests\Jojo1981\TypedCollection\Tests;

use Jojo1981\TypedCollection\Collection;
use Jojo1981\TypedCollection\Exception\CollectionException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use tests\Jojo1981\TypedCollection\Fixtures\InterfaceTestEntity;
use tests\Jojo1981\TypedCollection\Fixtures\TestEntity;
use tests\Jojo1981\TypedCollection\Fixtures\TestEntityBase;

/**
 * @package tests\Jojo1981\TypedCollection\Tests
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
     * @dataProvider getPrimitiveTypeWithInvalidData
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
     * @dataProvider getPrimitiveTypeWithInvalidData
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

    /**
     * @return array[]
     */
    public function getPrimitiveTypeWithInvalidData(): array
    {
        return [
            ['string', ['text', 1], 'Data is not of expected type: `string`, but of type: `integer`']
        ];
    }

    /**
     * @return array[]
     */
    public function getClassNameTypeWithInvalidData(): array
    {
        return [
            [TestEntity::class, [new TestEntity(), 'text'], 'Data is not an instance of: `tests\Jojo1981\TypedCollection\Fixtures\TestEntity`, but of type: `string`']
        ];
    }
}