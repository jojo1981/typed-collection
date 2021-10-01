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
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use stdClass;
use Throwable;

/**
 * @package Jojo1981\TypedCollection\TestSuite\Test
 */
final class CollectionTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\CollectionDataProvider::getInvalidTypes()
     *
     * @param string $invalidType
     * @param Throwable|null $expectedPreviousException
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Exception
     * @throws CollectionException
     */
    public function constructWithInvalidTypeShouldThrowCollectionException(string $invalidType, ?Throwable $expectedPreviousException): void
    {
        $this->expectExceptionObject(CollectionException::typeIsNotValid($invalidType));
        try {
            new Collection($invalidType);
        } catch (Throwable $exception) {
            self::assertEquals($expectedPreviousException, $exception->getPrevious());
            throw $exception;
        }
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\CollectionDataProvider::getPrimitiveTypeWithInvalidData
     *
     * @param string $type
     * @param array $invalidData
     * @param string $message
     * @return void
     * @throws CollectionException
     */
    public function constructWithValidPrimitiveTypeButInvalidElementShouldThrowCollectionException(
        string $type,
        array $invalidData,
        string $message
    ): void {
        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(0);
        new Collection($type, $invalidData);
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\CollectionDataProvider::getClassNameTypeWithInvalidData
     *
     * @param string $type
     * @param array $invalidData
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
        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode(0);
        new Collection($type, $invalidData);
    }

    /**
     * @test
     * @dataProvider \Jojo1981\TypedCollection\TestSuite\DataProvider\CollectionDataProvider::getValidTypesMap
     *
     * @param string $type
     * @param string $expectedType
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return void
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
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return void
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
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return void
     */
    public function isEqualTypeShouldReturnTrueWhenTypeStrictlyMatches(string $typeA, string $typeB): void
    {
        $this->assertTrue((new Collection($typeA))->isEqualType(new Collection($typeB)));
    }

    /**
     * @test
     *
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function isEmptyShouldReturnTrueWhenTheCollectionIsEmpty(): void
    {
        $this->assertTrue((new Collection('string'))->isEmpty());
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return void
     */
    public function isEmptyShouldReturnFalseWhenTheCollectionIsNotEmpty(): void
    {
        $this->assertFalse((new Collection('string', ['item1']))->isEmpty());
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return void
     */
    public function isNonEmptyShouldReturnFalseWhenTheCollectionIsNotEmpty(): void
    {
        $this->assertFalse((new Collection('string'))->isNonEmpty());
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return void
     */
    public function isNonEmptyShouldReturnTrueWhenTheCollectionIsEmpty(): void
    {
        $this->assertTrue((new Collection('string', ['item1']))->isNonEmpty());
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('Given type: `invalidType` is not a valid primitive type and also not an existing class');
        $this->expectExceptionCode(0);
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
        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('An empty array with typed collections passed');
        $this->expectExceptionCode(0);
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
        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('At least 2 collections needs to be passed');
        $this->expectExceptionCode(0);
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
        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('Expect $collections array to contain instances of Collection');
        $this->expectExceptionCode(0);
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
        $this->expectException(CollectionException::class);
        $this->expectExceptionMessage('Expect every collection to be of type: `string`. Collection found with type: `int`');
        $this->expectExceptionCode(0);
        Collection::createFromCollections('string', [new Collection('string'), new Collection('int')]);
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return void
     */
    public function isEqualCollectionShouldReturnFalseWhenCollectionTypesDoNotMatch(): void
    {
        $collection1 = new Collection('string');
        $collection2 = new Collection('integer');
        $predicate = function (): bool {
            $this->fail('Predicate callback should not be invoked');
        };

        $this->assertFalse($collection1->isEqualCollection($collection2, $predicate, true));
        $this->assertFalse($collection1->isEqualCollection($collection2, $predicate));
        $this->assertFalse($collection2->isEqualCollection($collection1, $predicate, true));
        $this->assertFalse($collection2->isEqualCollection($collection1, $predicate));
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return void
     */
    public function isEqualCollectionShouldReturnFalseWhenCollectionTypesAreMatchingButCountIsNotTheSame(): void
    {
        $collection1 = new Collection('string', ['a', 'b']);
        $collection2 = new Collection('string', ['a']);
        $predicate = function (): bool {
            $this->fail('Predicate callback should not be invoked');
        };

        $this->assertFalse($collection1->isEqualCollection($collection2, $predicate, true));
        $this->assertFalse($collection1->isEqualCollection($collection2, $predicate));
        $this->assertFalse($collection2->isEqualCollection($collection1, $predicate, true));
        $this->assertFalse($collection2->isEqualCollection($collection1, $predicate));
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return void
     */
    public function isEqualCollectionShouldReturnTrueWhenCollectionTypesAreMatchingAndCollectionsAreEmpty(): void
    {
        $collection1 = new Collection('string');
        $collection2 = new Collection('string');
        $predicate = function (): bool {
            $this->fail('Predicate callback should not be invoked');
        };

        $this->assertTrue($collection1->isEqualCollection($collection2, $predicate, true));
        $this->assertTrue($collection1->isEqualCollection($collection2, $predicate));
        $this->assertTrue($collection2->isEqualCollection($collection1, $predicate, true));
        $this->assertTrue($collection2->isEqualCollection($collection1, $predicate));
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
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
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return void
     */
    public function isEqualCollectionShouldReturnFalseWhenNoPredicateIsGivenAndCollectionsAreNotEqualBecauseOfStrictlyEqualsComparision(): void
    {
        $collection1 = new Collection(stdClass::class, [new stdClass()]);
        $collection2 = new Collection(stdClass::class, [new stdClass()]);

        $this->assertFalse($collection1->isEqualCollection($collection2));
        $this->assertFalse($collection1->isEqualCollection($collection2, null, true));
        $this->assertFalse($collection2->isEqualCollection($collection1));
        $this->assertFalse($collection2->isEqualCollection($collection1, null, true));
    }

    /**
     * @test
     *
     * @throws CollectionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return void
     */
    public function isEqualCollectionShouldReturnTrueWhenNoPredicateIsGivenAndCollectionsAreEqualEvenWithStrictlyEqualsComparision(): void
    {
        $element = new stdClass();
        $collection2 = new Collection(stdClass::class, [$element]);
        $collection1 = new Collection(stdClass::class, [$element]);

        $this->assertTrue($collection1->isEqualCollection($collection2));
        $this->assertTrue($collection1->isEqualCollection($collection2, null, true));
        $this->assertTrue($collection2->isEqualCollection($collection1));
        $this->assertTrue($collection2->isEqualCollection($collection1, null, true));
    }
}
