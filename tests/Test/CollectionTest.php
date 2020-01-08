<?php declare(strict_types=1);
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
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\CollectionDataProvider::getInvalidTypes()
     *
     * @param string $invalidType
     * @return void
     *@throws CollectionException
     */
    public function constructWithInvalidTypeShouldThrowCollectionException(string $invalidType): void
    {
        $this->expectExceptionObject(new CollectionException(
            'Given type: `' . $invalidType . '` is not a valid primitive type and also not an existing class'
        ));
        new Collection($invalidType);
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\CollectionDataProvider::getPrimitiveTypeWithInvalidData
     *
     * @param string $type
     * @param mixed[] $invalidData
     * @param string $message
     * @return void
     *@throws CollectionException
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
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\CollectionDataProvider::getClassNameTypeWithInvalidData
     *
     * @param string $type
     * @param mixed[] $invalidData
     * @param string $message
     * @return void
     *@throws CollectionException
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
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\CollectionDataProvider::getValidTypesMap
     *
     * @param string $type
     * @param string $expectedType
     * @return void
     *@throws InvalidArgumentException
     * @throws CollectionException
     * @throws ExpectationFailedException
     */
    public function getTypeShouldReturnTheCorrectType(string $type, string $expectedType): void
    {
        $this->assertEquals($expectedType, (new Collection($type))->getType());
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\CollectionDataProvider::getNotMatchingTypes
     *
     * @param string $typeA
     * @param string $typeB
     * @return void
     *@throws InvalidArgumentException
     * @throws CollectionException
     * @throws ExpectationFailedException
     */
    public function isEqualTypeShouldReturnFalseWhenTypeNotStrictlyMatches(string $typeA, string $typeB): void
    {
        $this->assertFalse((new Collection($typeA))->isEqualType(new Collection($typeB)));
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\CollectionDataProvider::getStrictlyMatchingTypes
     *
     * @param string $typeA
     * @param string $typeB
     * @return void
     *@throws InvalidArgumentException
     * @throws CollectionException
     * @throws ExpectationFailedException
     */
    public function isEqualTypeShouldReturnTrueWhenTypeStrictlyMatches(string $typeA, string $typeB): void
    {
        $this->assertTrue((new Collection($typeA))->isEqualType(new Collection($typeB)));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEmptyShouldReturnTrueWhenTheCollectionIsEmpty(): void
    {
        $this->assertTrue((new Collection('string'))->isEmpty());
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEmptyShouldReturnFalseWhenTheCollectionIsNotEmpty(): void
    {
        $this->assertFalse((new Collection('string', ['item1']))->isEmpty());
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isNonEmptyShouldReturnFalseWhenTheCollectionIsNotEmpty(): void
    {
        $this->assertFalse((new Collection('string'))->isNonEmpty());
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isNonEmptyShouldReturnTrueWhenTheCollectionIsEmpty(): void
    {
        $this->assertTrue((new Collection('string', ['item1']))->isNonEmpty());
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function unshiftElementShouldAddAnElementToTheBeginOfTheCollection(): void
    {
        $collection = new Collection('string', ['item1', 'item2']);
        $this->assertEquals(2, $collection->count());
        $collection->unshiftElement('item3');
        $this->assertEquals(3, $collection->count());

        $expectedOrder = ['item3', 'item1', 'item2'];
        $this->assertSame($expectedOrder, $collection->toArray());

        foreach ($collection as $index => $item) {
            $this->assertEquals($expectedOrder[$index], $item);
        }
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function pushElementShouldAddAnElementToTheEndOfTheCollection(): void
    {
        $collection = new Collection('string', ['item1', 'item2']);
        $this->assertEquals(2, $collection->count());
        $collection->pushElement('item3');
        $this->assertEquals(3, $collection->count());

        $expectedOrder = ['item1', 'item2', 'item3'];
        $this->assertSame($expectedOrder, $collection->toArray());

        foreach ($collection as $index => $item) {
            $this->assertEquals($expectedOrder[$index], $item);
        }
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
            'Expect every collection to be of type: `string`. Collection found with type: `int`'
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
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEqualCollectionShouldReturnFalseWhenCollectionTypesDoNotMatch(): void
    {
        $collection1 = new Collection('string');
        $collection2 = new Collection('integer');
        $predicate = function ($elementA, $elementB): bool {
            $this->fail('Predicate callback should not be invoked');

            return true;
        };

        $this->assertFalse($collection1->isEqualCollection($collection2, $predicate, true));
        $this->assertFalse($collection1->isEqualCollection($collection2, $predicate));
        $this->assertFalse($collection2->isEqualCollection($collection1, $predicate, true));
        $this->assertFalse($collection2->isEqualCollection($collection1, $predicate));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEqualCollectionShouldReturnFalseWhenCollectionTypesAreMatchingButCountIsNotTheSame(): void
    {
        $collection1 = new Collection('string', ['a', 'b']);
        $collection2 = new Collection('string', ['a']);
        $predicate = function ($elementA, $elementB): bool {
            $this->fail('Predicate callback should not be invoked');

            return true;
        };

        $this->assertFalse($collection1->isEqualCollection($collection2, $predicate, true));
        $this->assertFalse($collection1->isEqualCollection($collection2, $predicate));
        $this->assertFalse($collection2->isEqualCollection($collection1, $predicate, true));
        $this->assertFalse($collection2->isEqualCollection($collection1, $predicate));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEqualCollectionShouldReturnFalseWhenCollectionTypesAreMatchingCountIsTheSameButNotAllElementAreEqual(): void
    {
        $collection1 = new Collection('string', ['a', 'b', 'c']);
        $collection2 = new Collection('string', ['a', 'b', 'd']);
        $predicate = static function ($elementA, $elementB): bool {
            return $elementA === $elementB;
        };

        $this->assertFalse($collection1->isEqualCollection($collection2, $predicate, true));
        $this->assertFalse($collection1->isEqualCollection($collection2, $predicate));
        $this->assertFalse($collection2->isEqualCollection($collection1, $predicate, true));
        $this->assertFalse($collection2->isEqualCollection($collection1, $predicate));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEqualCollectionShouldReturnTrueWhenCollectionTypesAreMatchingAndCollectionsAreEmpty(): void
    {
        $collection1 = new Collection('string');
        $collection2 = new Collection('string');
        $predicate = function ($elementA, $elementB): bool {
            $this->fail('Predicate callback should not be invoked');

            return true;
        };

        $this->assertTrue($collection1->isEqualCollection($collection2, $predicate, true));
        $this->assertTrue($collection1->isEqualCollection($collection2, $predicate));
        $this->assertTrue($collection2->isEqualCollection($collection1, $predicate, true));
        $this->assertTrue($collection2->isEqualCollection($collection1, $predicate));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEqualCollectionShouldReturnFalseWhenStrictIsTrueAndElementsAreMatchingButNotInSameOrder(): void
    {
        $collection1 = new Collection('string', ['a', 'c', 'b']);
        $collection2 = new Collection('string', ['a', 'b', 'c']);
        $predicate = static function ($elementA, $elementB): bool {
            return $elementA === $elementB;
        };

        $this->assertFalse($collection1->isEqualCollection($collection2, $predicate, true));
        $this->assertFalse($collection2->isEqualCollection($collection1, $predicate, true));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEqualCollectionShouldReturnTrueWhenStrictIsTrueAndElementsAreMatchingAndInSameOrder(): void
    {
        $collection1 = new Collection('string', ['a', 'c', 'b']);
        $collection2 = new Collection('string', ['a', 'c', 'b']);
        $predicate = static function ($elementA, $elementB): bool {
            return $elementA === $elementB;
        };

        $this->assertTrue($collection1->isEqualCollection($collection2, $predicate, true));
        $this->assertTrue($collection2->isEqualCollection($collection1, $predicate, true));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEqualCollectionShouldReturnTrueWhenStrictIsFalseAndElementsAreMatchingButNotInSameOrder(): void
    {
        $collection1 = new Collection('string', ['a', 'c', 'b']);
        $collection2 = new Collection('string', ['a', 'b', 'c']);
        $predicate = static function ($elementA, $elementB): bool {
            return $elementA === $elementB;
        };

        $this->assertTrue($collection1->isEqualCollection($collection2, $predicate));
        $this->assertTrue($collection2->isEqualCollection($collection1, $predicate));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEqualCollectionShouldReturnFalseWhenNoPredicateIsGivenAndCollectionsAreNotEqual(): void
    {
        $collection1 = new Collection('string', ['a', 'c', 'b']);
        $collection2 = new Collection('string', ['a', 'b', 'd']);

        $this->assertFalse($collection1->isEqualCollection($collection2));
        $this->assertFalse($collection1->isEqualCollection($collection2, null, true));
        $this->assertFalse($collection2->isEqualCollection($collection1));
        $this->assertFalse($collection2->isEqualCollection($collection1, null, true));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEqualCollectionShouldReturnTrueWhenNoPredicateIsGivenAndCollectionsAreEqual(): void
    {
        $collection1 = new Collection('string', ['a', 'b', 'c']);
        $collection2 = new Collection('string', ['a', 'b', 'c']);

        $this->assertTrue($collection1->isEqualCollection($collection2));
        $this->assertTrue($collection1->isEqualCollection($collection2, null, true));
        $this->assertTrue($collection2->isEqualCollection($collection1));
        $this->assertTrue($collection2->isEqualCollection($collection1, null, true));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEqualCollectionShouldReturnCorrectResultWhenNoPredicateIsGivenAndCollectionsAreUnorderedEqual(): void
    {
        $collection1 = new Collection('string', ['a', 'b', 'c']);
        $collection2 = new Collection('string', ['a', 'c', 'b']);

        $this->assertTrue($collection1->isEqualCollection($collection2));
        $this->assertFalse($collection1->isEqualCollection($collection2, null, true));
        $this->assertTrue($collection2->isEqualCollection($collection1));
        $this->assertFalse($collection2->isEqualCollection($collection1, null, true));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEqualCollectionShouldReturnFalseWhenNoPredicateIsGivenAndCollectionsAreNotEqualBecauseOfStrictlyEqualsComparision(): void
    {
        $collection1 = new Collection(\stdClass::class, [new \stdClass()]);
        $collection2 = new Collection(\stdClass::class, [new \stdClass()]);

        $this->assertFalse($collection1->isEqualCollection($collection2));
        $this->assertFalse($collection1->isEqualCollection($collection2, null, true));
        $this->assertFalse($collection2->isEqualCollection($collection1));
        $this->assertFalse($collection2->isEqualCollection($collection1, null, true));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CollectionException
     * @return void
     */
    public function isEqualCollectionShouldReturnTrueWhenNoPredicateIsGivenAndCollectionsAreEqualEvenWithStrictlyEqualsComparision(): void
    {
        $element = new \stdClass();
        $collection2 = new Collection(\stdClass::class, [$element]);
        $collection1 = new Collection(\stdClass::class, [$element]);

        $this->assertTrue($collection1->isEqualCollection($collection2));
        $this->assertTrue($collection1->isEqualCollection($collection2, null, true));
        $this->assertTrue($collection2->isEqualCollection($collection1));
        $this->assertTrue($collection2->isEqualCollection($collection1, null, true));
    }
}