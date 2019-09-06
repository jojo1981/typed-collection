<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection;

use Jojo1981\TypedCollection\Exception\CollectionException;
use Jojo1981\TypedCollection\Value\Type\AbstractTypeValue;
use Jojo1981\TypedCollection\Value\Type\TypeValueInterface;

/**
 * This is a mutable collection and is a wrapper around an indexed array and can only hold elements of the same type.
 * The type will be determined at construction type and is not changeable afterwards.
 *
 * @api
 * @package Jojo1981\TypedCollection
 */
class Collection implements \Countable, \IteratorAggregate
{
    /** @var TypeValueInterface */
    private $type;

    /** @var mixed[] */
    private $elements = [];

    /**
     * Construct a new collection an set the type for this collection. The type of the collection can be any
     * primitive type or any class\interface type. Optionally some element can be given.
     *
     * @param string $type
     * @param mixed[] $elements (optional)
     * @throws CollectionException
     */
    public function __construct(string $type, array $elements = [])
    {
        static::assertType($type);
        $this->type = AbstractTypeValue::createTypeValueInstance($type);
        $this->pushElements($elements);
    }

    /**
     * Get the type of this collection.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type->getValue();
    }

    /**
     * Compare the collection type if this collection against a given collection and return true when the types are
     * exactly matching. The comparison here has nothing to do with the actual contents of the collections.
     *
     * @param Collection $otherCollection
     * @return bool
     */
    public function isEqualType(Collection $otherCollection): bool
    {
        return $this->type->isEqual($otherCollection->type);
    }

    /**
     * Return whether this collection is empty. A collection without elements is considered to be empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * Return whether this collection is not empty. A collection with a least 1 element is considered to be not empty.
     *
     * @return bool
     */
    public function isNonEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Add an element to the begin (prepend) of this collection.
     *
     * @param mixed $element
     * @throws CollectionException
     * @return void
     */
    public function unshiftElement($element): void
    {
        $this->assertElementIsValid($element);
        \array_unshift($this->elements, $element);
    }

    /**
     * Add an element to the end (append) of this collection.
     *
     * @param mixed $element
     * @throws CollectionException
     * @return void
     */
    public function pushElement($element): void
    {
        $this->assertElementIsValid($element);
        $this->elements[] = $element;
    }

    /**
     * Remove the first element of this collection and return that element, when this collection is empty null will be
     * returned instead.
     *
     * @return null|mixed
     */
    public function shiftElement()
    {
        return \array_shift($this->elements);
    }

    /**
     * Remove the last element of this collection and return the element, when this collection is empty null will
     * be returned instead.
     *
     * @return null|mixed
     */
    public function popElement()
    {
        return \array_pop($this->elements);
    }

    /**
     * Check if this collection contains the given element. This will be done by strict comparision.
     * When this collection contains objects the comparision will be done by reference. Use `some` when you need to
     * check if there is an element which matches a custom predicate.
     *
     * @param mixed $element
     * @throws CollectionException
     * @return bool
     */
    public function hasElement($element): bool
    {
        $this->assertElementIsValid($element);

        return \in_array($element, $this->elements, true);
    }

    /**
     * Add elements to the end (append) of this collection.
     *
     * @param mixed[] $elements
     * @throws CollectionException
     * @return void
     */
    public function pushElements(array $elements): void
    {
        foreach ($elements as $element) {
            $this->pushElement($element);
        }
    }

    /**
     * Add elements to the begin (prepend) of this collection.
     *
     * @param mixed[] $elements
     * @throws CollectionException
     * @return void
     */
    public function unshiftElements(array $elements): void
    {
        foreach (\array_reverse($elements) as $element) {
            $this->unshiftElement($element);
        }
    }

    /**
     * @param mixed[] $elements
     * @throws CollectionException
     * @return void
     */
    public function setElements(array $elements): void
    {
        $this->clear();
        $this->pushElements($elements);
    }

    /**
     * Remove the given element from this collection when it exists in this collection. When the element does not exists
     * in this collection a CollectionException will be thrown. Use Collection::hasElement to check if an element exist
     * in this collection.
     *
     * @param mixed $element
     * @throws CollectionException
     * @return void
     */
    public function removeElement($element): void
    {
        $this->assertElementIsValid($element);

        if (false !== $index = \array_search($element, $this->elements, true)) {
            unset($this->elements[$index]);
        } else {
            throw new CollectionException('Element doesn\'t exists in this collection');
        }
    }

    /**
     * Get the index (zero based position) of an element in this collection. When the element does not exists in this
     * collection null will be returned instead.
     *
     * @param mixed $element
     * @throws CollectionException
     * @return null|int
     */
    public function indexOfElement($element): ?int
    {
        $this->assertElementIsValid($element);
        if (false !== $index = \array_search($element, $this->elements, true)) {
            return $index;
        }

        return null;
    }

    /**
     * Get an element by the given index. Returns the element or null when the given index does not exists in this
     * collection.
     *
     * @param int $index
     * @return null|mixed
     */
    public function getElementByIndex(int $index)
    {
        return $this->elements[$index] ?? null;
    }

    /**
     * Get the first element of this collection. When this collection is empty null will be returned.
     *
     * @return null|mixed
     */
    public function getFirstElement()
    {
        $lastElement = \reset($this->elements);

        return false !== $lastElement ? $lastElement : null;
    }

    /**
     * Get the last element of this collection. When this collection is empty null will be returned.
     *
     * @return null|mixed
     */
    public function getLastElement()
    {
        $lastElement = \end($this->elements);

        return false !== $lastElement ? $lastElement : null;
    }

    /**
     * Get the first element of this collection as a new collection with 1 element. When this collection is empty a new
     * empty collection will be returned. The newly returned collection has the same type as this collection.
     *
     * @throws CollectionException
     * @return Collection
     */
    public function getFirstElementAsCollection(): Collection
    {
        $firstElement = $this->getFirstElement();

        return new Collection(
            $this->type->getValue(),
            $firstElement ? [$firstElement] : []
        );
    }

    /**
     * Get all elements from this collection as an indexed array.
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        return \array_values($this->elements);
    }

    /**
     * Sort this collection using the passed comparator callback. The comparator callback should accept 2 parameters
     * which are matching the type of this collection and the callback should return an integer higher than,
     * less than or equal to zero.
     *
     * @param callable $comparator
     * @throws CollectionException
     * @return Collection
     */
    public function sortBy(callable $comparator): Collection
    {
        $elements = $this->elements;
        if (false === \usort($elements, $comparator)) {
            throw new CollectionException('Could not sort the collection');
        }

        return new Collection($this->getType(), $elements);
    }

    /**
     * Map this collection to a new collection of the given type.
     *
     * The mapper callback should accept 2 parameters at max. The first parameter should accept the same type as this
     * collection has and the second parameter is optionally and must accept an integer. The return type of the mapper
     * should be the same type as the type given for the new collection to be returned.
     *
     * @param string $type
     * @param callable $mapper
     * @throws CollectionException
     * @return Collection
     */
    public function map(string $type, callable $mapper): Collection
    {
        static::assertType($type);
        $elements = $this->toArray();

        return new Collection(
            $type,
            \array_map(
                static function (int $index) use ($elements, $mapper) {
                    return $mapper($elements[$index], $index);
                },
                \array_keys($elements)
            )
        );
    }

    /**
     * Flat map this collection to a new collection of the given type.
     *
     * The mapper callback should accept 2 parameters at max. The first parameter should accept the same type as this
     * collection has and the second parameter is optionally and must accept an integer. The return type of the mapper
     * should be an array. The returned value for the mapper should be an empty array or an array with elements of the
     * same type as the type given for the new collection to be returned.
     *
     * @param string $type
     * @param callable $mapper
     * @throws CollectionException
     * @return Collection
     */
    public function flatMap(string $type, callable $mapper): Collection
    {
        static::assertType($type);
        $result = [];
        foreach ($this->elements as $index => $value) {
            \array_push($result, ...$mapper($value, $index));
        }

        return new Collection($type, $result);
    }

    /**
     * Merge other collection into this collection. Both collections should be exactly of the same type.
     *
     * @param Collection $otherCollection
     * @param Collection ...$otherCollections
     * @throws CollectionException
     * @return void
     */
    public function merge(Collection $otherCollection, Collection ...$otherCollections): void
    {
        \array_unshift($otherCollections, $otherCollection);

        foreach ($otherCollections as $currentOtherCollection) {
            if (!$this->type->match($currentOtherCollection->type)) {
                throw CollectionException::couldNotMergeCollection($this->type->getValue(), $otherCollection->getType());
            }

            $this->pushElements($currentOtherCollection->toArray());
        }
    }

    /**
     * Apply the predicate for all elements in this collection and return true when the predicate has returned true for all
     * elements or when this collection is empty. The predicate callback should accept 2 parameters at max.
     * The first parameter should accept the same type as this collection has and the second parameter is optionally and
     * must accept an integer. The return type of the predicate should be a boolean.
     *
     * @param callable $predicate
     * @return bool
     */
    public function all(callable $predicate): bool
    {
        foreach ($this->elements as $index => $element) {
            if (false === $predicate($element, $index)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Apply the predicate for all elements in this collection and return true when the predicate has returned true for an
     * element. When this collection is empty false will be returned. As soon as the predicate has returned true the
     * iteration will be stopped and true will be returned. The predicate callback should accept 2 parameters at max.
     * The first parameter should accept the same type as this collection has and the second parameter is optionally and
     * must accept an integer. The return type of the predicate should be a boolean.
     *
     * @param callable $predicate
     * @return bool
     */
    public function some(callable $predicate): bool
    {
        foreach ($this->elements as $index => $element) {
            if (true === $predicate($element, $index)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Apply the predicate for all elements in this collection and return true when the predicate has returned false for all
     * elements or when this collection is empty. As soon as the predicate has returned true the iteration will be
     * stopped and false will be returned. The predicate callback should accept 2 parameters at max.
     * The first parameter should accept the same type as this collection has and the second parameter is optionally and
     * must accept an integer. The return type of the predicate should be a boolean.
     *
     * @param callable $predicate
     * @return bool
     */
    public function none(callable $predicate): bool
    {
        foreach ($this->elements as $index => $element) {
            if (true === $predicate($element, $index)) {
                return false;
            }
        }

        return true;
    }

    /**
     * For each element the callback will be invoked. The callback should accept 2 parameters at max.
     * The first parameter should accept the same type as this collection has and the second parameter is optionally and
     * must accept an integer. The return type of the callback should be void.
     *
     * @param callable $callback
     * @return void
     */
    public function forEach(callable $callback): void
    {
        foreach ($this->elements as $index => $element) {
            $callback($element, $index);
        }
    }

    /**
     * Will fold to collection from the left side into 1 single value. The callback should accept 3 parameters
     * at max. The first parameter type should match the result type and/or initial value, The second parameter should
     * accept the same type as this collection has and the third parameter is optionally and must accept an integer.
     * The return type of the callback should be the same type as the first parameter.
     *
     * @param callable $callback
     * @param null|mixed $initial
     * @return mixed
     */
    public function foldLeft(callable $callback, $initial = null)
    {
        $result = $initial;
        foreach ($this->elements as $index => $element) {
            $result = $callback($result, $element, $index);
        }

        return $result;
    }

    /**
     * Will fold to collection from the right side into 1 single value. The callback should accept 3 parameters
     * at max. The first parameter type should match the result type and/or initial value, The second parameter should
     * accept the same type as this collection has and the third parameter is optionally and must accept an integer.
     * The return type of the callback should be the same type as the first parameter.
     *
     * @param callable $callback
     * @param null|mixed $initial
     * @return mixed
     */
    public function foldRight(callable $callback, $initial = null)
    {
        $result = $initial;
        foreach (\array_reverse($this->elements) as $index => $element) {
            $result = $callback($result, $element, $index);
        }

        return $result;
    }

    /**
     * Filter this collection and return a new collection of the same type as this collection with the filtered elements.
     * Apply the predicate for all elements in this collection and when the predicate returns true the elements will be
     * added to the new collection. When the predicate never matches an empty collection will be the result.
     * The predicate callback should accept 2 parameters at max. The first parameter should accept the same type as this
     * collection has and the second parameter is optionally and must accept an integer. The return type of the predicate
     * should be a boolean.
     *
     * @param callable $predicate
     * @throws CollectionException
     * @return Collection
     */
    public function filter(callable $predicate): Collection
    {
        return new Collection($this->type->getValue(), \array_filter($this->elements, $predicate, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * Apply the predicate for all elements in this collection and return the element when the predicate matches.
     * Return null when the predicate has never returned true. The first element which matches the predicate will be
     * returned. This collection will be untouched and is not mutated.
     * As soon as the predicate has returned true the iteration will be stopped and the element will be returned.
     * The predicate callback should accept 2 parameters at max. The first parameter should accept the same type as this
     * collection has and the second parameter is optionally and must accept an integer. The return type of the predicate
     * should be a boolean.
     *
     * @param callable $predicate
     * @return null|mixed
     */
    public function find(callable $predicate)
    {
        foreach ($this->elements as $index => $element) {
            if (true === $predicate($element, $index)) {
                return $element;
            }
        }

        return null;
    }

    /**
     * Return a new Collection of type Collection::class with multiple Collection elements in it.
     * At least 1 predicate must be given. The new Collection have the number of predicate plus 1 as Collection elements.
     * The predicate indexes will be used to add elements to the right collection when the predicate matches.
     * When the predicate is not matching for an element the element will be added into the last Collection element of the
     * Return Collection.
     *
     * The predicate callback should accept 2 parameters at max. The first parameter should accept the same type as this
     * collection has and the second parameter is optionally and must accept an integer. The return type of the predicate
     * should be a boolean.
     *
     * @param callable $predicate
     * @param callable ...$predicates
     * @throws CollectionException
     * @return Collection
     */
    public function group(callable $predicate, callable ...$predicates): Collection
    {
        \array_unshift($predicates, $predicate);
        $numPredicates = \count($predicates);

        return $this->mapCollectionArraysToCollection(
            \array_reduce(
                \array_keys($this->elements),
                function (array $result, int $key) use ($predicates, $numPredicates): array {
                    $value = $this->elements[$key];
                    foreach ($predicates as $index => $predicate) {
                        if ($predicate($value, $key)) {
                            $result[$index] = $value;

                            return $result;
                        }
                    }
                    $result[$numPredicates - 1] = $value;

                    return $result;
                },
                \array_fill(0, $numPredicates, [])
            )
        );
    }

    /**
     * Return a new Collection of type Collection::class (a Collection of Collection elements).
     * The new returned collection has 2 Collection elements. The first element contains a collection with all elements
     * matches by the predicate. The second collection element has all not matched elements.
     *
     * The predicate callback should accept 2 parameters at max. The first parameter should accept the same type as this
     * collection has and the second parameter is optionally and must accept an integer. The return type of the predicate
     * should be a boolean.
     *
     * @param callable $predicate
     * @throws CollectionException
     * @return Collection
     */
    public function partition(callable $predicate): Collection
    {
        return $this->mapCollectionArraysToCollection(
            \array_reduce(
                \array_keys($this->elements),
                function (array $result, int $index) use ($predicate): array {
                    $element = $this->elements[$index];
                    $result[$predicate($element, $index) ? 0 : 1] = $element;

                    return $result;
                },
                [[], []]
            )
        );
    }

    /**
     * Extract a slice of the elements this collection contains and return it as a new Collection of the same type as
     * this collection.
     *
     * The offset must be a negative or non-negative integer.
     * The length is optional an can be a negative or non-negative integer.
     *
     * Offset:
     *
     *  - non-negative offset: The sequence will start at that offset in this collection.
     *  - negative offset:     The sequence will start that far from the end of this collection.
     *
     * Length (optional):
     *
     *  - positive: The sequence will have up to that many elements in it.
     *              If this collection is shorter than the length, then only the available elements will be present.
     *  - negative: The sequence will stop that many elements from the end of this collection.
     *  - omitted:  The sequence will have everything from offset up until the end of this collection.
     *
     *
     * @param int $offset
     * @param null|int $length
     * @throws CollectionException
     * @return Collection
     */
    public function slice(int $offset, ?int $length = null): Collection
    {
        return new Collection(
            __CLASS__,
            \array_slice(
                $this->elements,
                $offset,
                $length
            )
        );
    }

    /**
     * Remove all elements from this collection. This collection will be empty afterwards.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->elements = [];
    }

    /**
     * Return the number of elements this collection contains.
     *
     * @return int
     */
    public function count(): int
    {
        return \count($this->elements);
    }

    /**
     * Return a collection iterator.
     *
     * @return CollectionIterator
     */
    public function getIterator(): CollectionIterator
    {
        return new CollectionIterator(new \ArrayIterator($this->elements));
    }

    /**
     * Check whether the passed collection is equal to this collection. When $strict is true also the order of elements
     * needs to be the same. A collection is considered to be equal when both collections are of the same type, have the
     * same count and all elements from this collection can be found in the passed Collection. To determine if 2 elements
     * should be considered equal the predicate function will be invoked.
     * The predicate function must accept 2 arguments of the same type this collection and should return a boolean value.
     * When the predicate function is omitted the default comparison will be strictly equals (=== comparison operator will
     * be used).
     *
     * @param Collection $otherCollection
     * @param null|callable $predicate
     * @param bool $strict
     * @return bool
     */
    public function isEqualCollection(Collection $otherCollection, ?callable $predicate = null, bool $strict = false): bool
    {
        $predicate = $predicate ?? static function ($left, $right): bool {
            return $left === $right;
        };

        return $strict ? $this->isEqualCollectionOrdered($otherCollection, $predicate)
            : $this->isEqualCollectionUnordered($otherCollection, $predicate);
    }

    /**
     * Compare if this collection and the passed collection are equal. The collections are considered equal when all
     * elements of this collection are found in the passed collection in the same order. The predicate callback is used
     * to determine if 2 elements should be considered equal.
     *
     * @param Collection $otherCollection
     * @param callable $predicate
     * @return bool
     */
    private function isEqualCollectionOrdered(Collection $otherCollection, callable $predicate): bool
    {
        if ($this->isNotEqualTypeOrHaveDifferentSize($otherCollection)) {
            return false;
        }
        if ($this->isEmpty()) {
            return true;
        }

        $otherElements = $otherCollection->toArray();
        foreach ($this->toArray() as $index => $element) {
            if (false === $predicate($element, $otherElements[$index])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Compare if this collection and the passed collection are equal. The collections are considered equal when all
     * elements of this collection are found in the passed collection not necessary in the same order. The predicate
     * callback is used to determine if 2 elements should be considered equal.
     *
     * @param Collection $otherCollection
     * @param callable $predicate
     * @return bool
     */
    private function isEqualCollectionUnordered(Collection $otherCollection, callable $predicate): bool
    {
        if ($this->isNotEqualTypeOrHaveDifferentSize($otherCollection)) {
            return false;
        }
        if ($this->isEmpty()) {
            return true;
        }

        $leftElements = $this->toArray();
        $rightElements = $otherCollection->toArray();
        while (!empty($leftElements)) {
            $leftElement = \array_pop($leftElements);
            $found = false;
            foreach ($rightElements as $index => $rightElement) {
                if (true === $predicate($leftElement, $rightElement)) {
                    unset($rightElements[$index]);
                    $found = true;
                    break;
                }
            }

            if (false === $found) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Collection $otherCollection
     * @return bool
     */
    private function isNotEqualTypeOrHaveDifferentSize(Collection $otherCollection): bool
    {
        return !$this->isEqualType($otherCollection) || $this->count() !== $otherCollection->count();
    }

    /**
     * Create one collection from multiple collections.
     *
     * @param string $type
     * @param Collection[] $collections
     * @throws CollectionException
     * @return Collection
     */
    public static function createFromCollections(string $type, array $collections): Collection
    {
        static::assertType($type);
        $typeValue = AbstractTypeValue::createTypeValueInstance($type);
        self::assertCollections($collections, $typeValue);

        $result = new Collection($typeValue->getValue());
        foreach ($collections as $collection) {
            $result->merge($collection);
        }

        return $result;
    }

    /**
     * @param mixed $element
     * @throws CollectionException
     * @return void
     */
    private function assertElementIsValid($element): void
    {
        $validationResult = $this->type->isValidData($element);
        if (false === $validationResult->isValid()) {
            throw new CollectionException($validationResult->getMessage());
        }
    }

    /**
     * @param array[] $collectionArrays
     * @throws CollectionException
     * @return Collection
     */
    private function mapCollectionArraysToCollection(array $collectionArrays): Collection
    {
        return new Collection(
            __CLASS__,
            \array_map(
                function (array $elements): Collection {
                    return new Collection($this->type->getValue(), $elements);
                },
                $collectionArrays
            )
        );
    }

    /**
     * @param Collection[] $collections
     * @param TypeValueInterface $typeValue
     * @throws CollectionException
     * @return void
     */
    private static function assertCollections(array $collections, TypeValueInterface $typeValue): void
    {
        if (empty($collections)) {
            throw new CollectionException('An empty array with typed collections passed');
        }
        if (\count($collections) < 2) {
            throw new CollectionException('At least 2 collections needs to be passed');
        }
        foreach ($collections as $collection) {
            if (!$collection instanceof self) {
                throw new CollectionException('Expect $collections array to contain instances of Collection');
            }
            if (!$typeValue->match($collection->type)) {
                throw new CollectionException(\sprintf(
                    'Expect every collection to be of type: `%s`. Collection found with type: `%s`',
                    $typeValue->getValue(),
                    $collection->type->getValue()
                ));
            }
        }
    }

    /**
     * @param string $type
     * @throws CollectionException
     * @return void
     */
    private static function assertType(string $type): void
    {
        if (!AbstractTypeValue::isValidValue($type)) {
            throw CollectionException::typeIsNotValid($type);
        }
    }
}
