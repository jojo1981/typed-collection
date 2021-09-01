<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Jojo1981\PhpTypes\AbstractType;
use Jojo1981\PhpTypes\Exception\TypeException;
use Jojo1981\PhpTypes\TypeInterface;
use Jojo1981\TypedCollection\Exception\CollectionException;
use RuntimeException;
use function array_fill;
use function array_filter;
use function array_keys;
use function array_map;
use function array_pop;
use function array_push;
use function array_reduce;
use function array_reverse;
use function array_search;
use function array_shift;
use function array_slice;
use function array_unshift;
use function array_values;
use function count;
use function end;
use function in_array;
use function is_array;
use function reset;
use function sprintf;
use function strtolower;
use function usort;

/**
 * This is a mutable collection and is a wrapper around an indexed array and can only hold elements of the same type.
 * The type will be determined at construction type and is not changeable afterwards.
 *
 * @api
 * @package Jojo1981\TypedCollection
 * @template T
 */
class Collection implements Countable, IteratorAggregate
{
    /** @var TypeInterface */
    private TypeInterface $type;

    /** @var T[] */
    private array $elements = [];

    /**
     * Construct a new collection and set the type for this collection. The type of the collection can be any
     * primitive type or any class or interface type. Optionally some element can be given.
     *
     * @param string $type
     * @param T[] $elements (optional)
     * @throws CollectionException
     * @throws RuntimeException
     */
    public function __construct(string $type, array $elements = [])
    {
        static::assertType($type);
        $this->type = self::createTypeFromName($type);
        $this->pushElements($elements);
    }

    /**
     * Get the type of this collection.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type->getName();
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
     * Return whether this collection is not empty. A collection with at least 1 element is considered to be not empty.
     *
     * @return bool
     */
    public function isNonEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Add an element to the beginning (prepend) of this collection.
     *
     * @param mixed $element
     * @return $this
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function unshiftElement($element): self
    {
        $this->assertElementIsValid($element);
        array_unshift($this->elements, $element);

        return $this;
    }

    /**
     * Add an element to the end (append) of this collection.
     *
     * @param mixed $element
     * @return $this
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function pushElement($element): self
    {
        $this->assertElementIsValid($element);
        $this->elements[] = $element;

        return $this;
    }

    /**
     * Remove the first element of this collection and return that element, when this collection is empty null will be
     * returned instead.
     *
     * @return T|null
     */
    public function shiftElement()
    {
        return array_shift($this->elements);
    }

    /**
     * Remove the last element of this collection and return the element, when this collection is empty null will
     * be returned instead.
     *
     * @return T|null
     */
    public function popElement()
    {
        return array_pop($this->elements);
    }

    /**
     * Check if this collection contains the given element. This will be done by strict comparison.
     * When this collection contains objects the comparison will be done by reference. Use `some` when you need to
     * check if there is an element which matches a custom predicate.
     *
     * @param T $element
     * @return bool
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function hasElement($element): bool
    {
        $this->assertElementIsValid($element);

        return in_array($element, $this->elements, true);
    }

    /**
     * Add elements to the end (append) of this collection.
     *
     * @param T[] $elements
     * @return $this
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function pushElements(array $elements): self
    {
        foreach ($elements as $element) {
            $this->pushElement($element);
        }

        return $this;
    }

    /**
     * Add elements to the beginning (prepend) of this collection.
     *
     * @param T[] $elements
     * @return $this
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function unshiftElements(array $elements): self
    {
        foreach (array_reverse($elements) as $element) {
            $this->unshiftElement($element);
        }

        return $this;
    }

    /**
     * @param T[] $elements
     * @return $this
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function setElements(array $elements): self
    {
        $this->clear();
        $this->pushElements($elements);

        return $this;
    }

    /**
     * Remove the given element from this collection when it exists in this collection. When the element does not exist
     * in this collection a CollectionException will be thrown. Use Collection::hasElement to check if an element exist
     * in this collection.
     *
     * @param mixed $element
     * @return $this
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function removeElement($element): self
    {
        $this->assertElementIsValid($element);

        if (false !== $index = array_search($element, $this->elements, true)) {
            unset($this->elements[$index]);
        } else {
            throw CollectionException::noSuchElement();
        }

        return $this;
    }

    /**
     * Get the index (zero based position) of an element in this collection. When the element does not exist in this
     * collection null will be returned instead.
     *
     * @param T $element
     * @return int|null
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function indexOfElement($element): ?int
    {
        $this->assertElementIsValid($element);
        if (false !== $index = array_search($element, $this->elements, true)) {
            return $index;
        }

        return null;
    }

    /**
     * Returns a new Collection with all elements in reverse order.
     *
     * @return Collection
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function reverse(): Collection
    {
        return new Collection($this->type->getName(), array_reverse($this->elements));
    }

    /**
     * Returns a new Collection by omitting the given number of elements from the beginning.
     * If the passed number is greater than the available number of elements, all will be removed.
     *
     * @param int $number
     * @return Collection
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function drop(int $number): Collection
    {
        if ($number <= 0) {
            throw new InvalidArgumentException(sprintf('The number must be greater than 0, but got %d.', $number));
        }

        return new Collection($this->type->getName(), array_slice($this->elements, $number));
    }

    /**
     * Returns a new Collection by omitting the given number of elements from the end.
     * If the passed number is greater than the available number of elements, all will be removed.
     *
     * @param int $number
     * @return Collection
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function dropRight(int $number): Collection
    {
        if ($number <= 0) {
            throw new InvalidArgumentException(sprintf('The number must be greater than 0, but got %d.', $number));
        }

        return new Collection($this->type->getName(), array_slice($this->elements, 0, -1 * $number));
    }

    /**
     * Returns a new Collection by omitting elements from the beginning for as long as the callable returns true.
     * The callback function receives the element to drop as first argument, and returns true (drop), or false (stop).
     *
     * @param callable $callback
     * @throws CollectionException
     * @throws RuntimeException
     * @return Collection
     */
    public function dropWhile(callable $callback): Collection
    {
        $countElements = $this->count();
        for ($currentIndex = 0; $currentIndex < $countElements; $currentIndex++) {
            if (true !== $callback($this->elements[$currentIndex])) {
                break;
            }
        }

        return new Collection($this->type->getName(), array_slice($this->elements, $currentIndex));
    }

    /**
     * Creates a new Collection by taking the given number of elements from the beginning
     * of the current Collection.
     *
     * If the passed number is greater than the available number of elements, then all elements
     * will be returned as a new collection.
     *
     * @param int $number
     * @throws CollectionException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return Collection
     */
    public function take(int $number): Collection
    {
        if ($number <= 0) {
            throw new InvalidArgumentException(sprintf('$number must be greater than 0, but got %d.', $number));
        }

        return new Collection($this->type->getName(), array_slice($this->elements, 0, $number));
    }

    /**
     * Creates a new Collection by taking elements from the current Collection
     * for as long as the callable returns true.
     *
     * @param callable $callable
     * @throws CollectionException
     * @throws RuntimeException
     * @return Collection
     */
    public function takeWhile(callable $callable): Collection
    {
        $newElements = [];
        $countElements = $this->count();
        for ($currentIndex = 0; $currentIndex < $countElements; $currentIndex++) {
            if (true !== $callable($this->elements[$currentIndex])) {
                break;
            }
            $newElements[] = $this->elements[$currentIndex];
        }

        return new Collection($this->type->getName(), $newElements);
    }

    /**
     * Get an element by the given index. Returns the element or null when the given index does not exist in this
     * collection.
     *
     * @param int $index
     * @return T|null
     */
    public function getElementByIndex(int $index)
    {
        return $this->elements[$index] ?? null;
    }

    /**
     * Get the first element of this collection. When this collection is empty null will be returned.
     *
     * @return T|null
     */
    public function getFirstElement()
    {
        $lastElement = reset($this->elements);

        return false !== $lastElement ? $lastElement : null;
    }

    /**
     * Get the last element of this collection. When this collection is empty null will be returned.
     *
     * @return T|null
     */
    public function getLastElement()
    {
        $lastElement = end($this->elements);

        return false !== $lastElement ? $lastElement : null;
    }

    /**
     * Get the first element of this collection as a new collection with 1 element. When this collection is empty a new
     * empty collection will be returned. The newly returned collection has the same type as this collection.
     *
     * @throws CollectionException
     * @throws RuntimeException
     * @return Collection
     */
    public function getFirstElementAsCollection(): Collection
    {
        $firstElement = $this->getFirstElement();

        return new Collection(
            $this->type->getName(),
            $firstElement ? [$firstElement] : []
        );
    }

    /**
     * Get all elements from this collection as an indexed array.
     *
     * @return T[]
     */
    public function toArray(): array
    {
        return array_values($this->elements);
    }

    /**
     * Sort this collection using the passed comparator callback. The comparator callback should accept 2 parameters
     * which are matching the type of this collection and the callback should return an integer higher than,
     * less than or equal to zero.
     *
     * @param callable $comparator
     * @throws CollectionException
     * @throws RuntimeException
     * @return Collection
     */
    public function sortBy(callable $comparator): Collection
    {
        $elements = $this->elements;
        if (false === usort($elements, $comparator)) {
            throw CollectionException::couldNotSortCollection();
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
     * @throws RuntimeException
     * @return Collection
     */
    public function map(string $type, callable $mapper): Collection
    {
        static::assertType($type);
        $elements = $this->toArray();

        return new Collection(
            $type,
            array_map(
                static function (int $index) use ($elements, $mapper) {
                    return $mapper($elements[$index], $index);
                },
                array_keys($elements)
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
     * @throws RuntimeException
     * @return Collection
     */
    public function flatMap(string $type, callable $mapper): Collection
    {
        static::assertType($type);
        $results = [];
        foreach ($this->elements as $index => $value) {
            $mapperResult = $mapper($value, $index);
            $values = !is_array($mapperResult) ? [$mapperResult] : array_values($mapperResult);
            if (!empty($values)) {
                array_push($results, ...$values);
            }
        }

        return new Collection($type, $results);
    }

    /**
     * Merge other collection into this collection. Both collections should be exactly of the same type.
     *
     * @param Collection $otherCollection
     * @param Collection ...$otherCollections
     * @return $this
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function merge(Collection $otherCollection, Collection ...$otherCollections): self
    {
        array_unshift($otherCollections, $otherCollection);

        foreach ($otherCollections as $currentOtherCollection) {
            if (!$this->type->isAssignableType($currentOtherCollection->type)) {
                throw CollectionException::couldNotMergeCollection($this->type->getName(), $otherCollection->getType());
            }

            $this->pushElements($currentOtherCollection->toArray());
        }

        return $this;
    }

    /**
     * Apply the predicate for all elements in this collection and return true when the predicate has returned true for all
     * elements or when this collection is empty. The predicate callback should accept 2 parameters at max.
     * The first parameter should accept the same type as this collection has and the second parameter is optionally and
     * must accept an integer. The return type of the predicate should be a boolean value.
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
     * must accept an integer. The return type of the predicate should be a boolean value.
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
     * must accept an integer. The return type of the predicate should be a boolean value.
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
     * @return $this
     */
    public function forEach(callable $callback): self
    {
        foreach ($this->elements as $index => $element) {
            $callback($element, $index);
        }

        return $this;
    }

    /**
     * Will fold the collection from the left side into 1 single value. The callback should accept 3 parameters
     * at max. The first parameter type should match the result type and/or initial value, The second parameter should
     * accept the same type as this collection has and the third parameter is optionally and must accept an integer.
     * The return type of the callback should be the same type as the first parameter.
     *
     * @param callable $callback
     * @param T|null $initial
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
     * Will fold the collection from the right side into 1 single value. The callback should accept 3 parameters
     * at max. The first parameter type should match the result type and/or initial value, The second parameter should
     * accept the same type as this collection has and the third parameter is optionally and must accept an integer.
     * The return type of the callback should be the same type as the first parameter.
     *
     * @param callable $callback
     * @param T|null $initial
     * @return mixed
     */
    public function foldRight(callable $callback, $initial = null)
    {
        $result = $initial;
        foreach (array_reverse($this->elements) as $index => $element) {
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
     * should be a boolean value.
     *
     * @param callable $predicate
     * @throws CollectionException
     * @throws RuntimeException
     * @return Collection
     */
    public function filter(callable $predicate): Collection
    {
        return new Collection($this->type->getName(), array_filter($this->elements, $predicate, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * Apply the predicate for all elements in this collection and return the element when the predicate matches.
     * Return null when the predicate has never returned true. The first element which matches the predicate will be
     * returned. This collection will be untouched and is not mutated.
     * As soon as the predicate has returned true the iteration will be stopped and the element will be returned.
     * The predicate callback should accept 2 parameters at max. The first parameter should accept the same type as this
     * collection has and the second parameter is optionally and must accept an integer. The return type of the predicate
     * should be a boolean value.
     *
     * @param callable $predicate
     * @return T|null
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
     * should be a boolean value.
     *
     * @param callable $predicate
     * @param callable ...$predicates
     * @return Collection
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function group(callable $predicate, callable ...$predicates): Collection
    {
        array_unshift($predicates, $predicate);
        $numPredicates = count($predicates);

        return $this->mapCollectionArraysToCollection(
            array_reduce(
                array_keys($this->elements),
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
                array_fill(0, $numPredicates, [])
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
     * should be a boolean value.
     *
     * @param callable $predicate
     * @return Collection
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function partition(callable $predicate): Collection
    {
        return $this->mapCollectionArraysToCollection(
            array_reduce(
                array_keys($this->elements),
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
     * The length is optional and can be a negative or non-negative integer.
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
     * @param int|null $length
     * @return Collection
     * @throws RuntimeException
     * @throws CollectionException
     */
    public function slice(int $offset, ?int $length = null): Collection
    {
        return new Collection(
            __CLASS__,
            array_slice(
                $this->elements,
                $offset,
                $length
            )
        );
    }

    /**
     * Remove all elements from this collection. This collection will be empty afterwards.
     *
     * @return $this
     */
    public function clear(): self
    {
        $this->elements = [];

        return $this;
    }

    /**
     * Return the number of elements this collection contains.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * Return a collection iterator.
     *
     * @return CollectionIterator
     */
    public function getIterator(): CollectionIterator
    {
        return new CollectionIterator(new ArrayIterator($this->elements));
    }

    /**
     * Check whether the passed collection is equal to this collection. When $strict is true also the order of elements
     * needs to be the same. A collection is considered to be equal when both collections are of the same type, have the
     * same count and all elements from this collection can be found the given Collection. To determine if 2 elements
     * should be considered equal the predicate function will be invoked.
     * The predicate function must accept 2 arguments of the same type this collection and should return a boolean value.
     * When the predicate function is omitted the default comparison will be strictly equals (=== comparison operator will
     * be used).
     *
     * @param Collection $otherCollection
     * @param callable|null $predicate
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
     * elements of this collection are found in the given collection in the same order. The predicate callback is used
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
     * elements of this collection are found in the given collection not necessary in the same order. The predicate
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
            $leftElement = array_pop($leftElements);
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
     * @return Collection
     * @throws RuntimeException
     * @throws CollectionException
     */
    public static function createFromCollections(string $type, array $collections): Collection
    {
        static::assertType($type);
        $typeValue = self::createTypeFromName($type);
        self::assertCollections($collections, $typeValue);

        $result = new Collection($typeValue->getName());
        foreach ($collections as $collection) {
            $result->merge($collection);
        }

        return $result;
    }

    /**
     * @param mixed $element
     * @return void
     * @throws RuntimeException
     * @throws CollectionException
     */
    private function assertElementIsValid($element): void
    {
        if (!$this->type->isAssignableValue($element)) {
            throw CollectionException::dataOfInvalidTypeGiven($this->type, self::createTypeFromValue($element));
        }
    }

    /**
     * @param array[] $collectionArrays
     * @throws CollectionException
     * @throws RuntimeException
     * @return Collection
     */
    private function mapCollectionArraysToCollection(array $collectionArrays): Collection
    {
        return new Collection(
            __CLASS__,
            array_map(
                function (array $elements): Collection {
                    return new Collection($this->type->getName(), $elements);
                },
                $collectionArrays
            )
        );
    }

    /**
     * @param Collection[] $collections
     * @param TypeInterface $typeValue
     * @throws CollectionException
     * @return void
     */
    private static function assertCollections(array $collections, TypeInterface $typeValue): void
    {
        if (empty($collections)) {
            throw CollectionException::emptyCollection();
        }
        if (count($collections) < 2) {
            throw CollectionException::notEnoughCollections();
        }
        foreach ($collections as $collection) {
            if (!$collection instanceof self) {
                throw CollectionException::invalidCollectionsData();
            }
            if (!$typeValue->isAssignableType($collection->type)) {
                throw CollectionException::collectionNotAllOfSameType($typeValue, $collection->type);
            }
        }
    }

    /**
     * @param string $type
     * @return void
     * @throws RuntimeException
     * @throws CollectionException
     */
    private static function assertType(string $type): void
    {
        if (in_array(strtolower($type), ['mixed', 'null', 'void'])) {
            throw CollectionException::typeIsNotValid($type);
        }

        self::createTypeFromName($type);
    }

    /**
     * @param array $elements
     * @return Collection
     * @throws RuntimeException
     * @throws CollectionException
     */
    public static function createFromElements(array $elements): Collection
    {
        if (empty($elements)) {
            throw CollectionException::emptyElementsCanNotDetermineType();
        }

        return new self((self::createTypeFromValue(reset($elements)))->getName(), $elements);
    }

    /**
     * @param mixed $value
     * @return TypeInterface
     * @throws RuntimeException
     * @throws CollectionException
     */
    private static function createTypeFromValue($value): TypeInterface
    {
        try {
            return AbstractType::createFromValue($value);
        } catch (TypeException $exception) {
            throw CollectionException::couldNotCreateTypeFromValue($exception);
        }
    }

    /**
     * @param string $name
     * @return TypeInterface
     * @throws RuntimeException
     * @throws CollectionException
     */
    private static function createTypeFromName(string $name): TypeInterface
    {
        try {
            return AbstractType::createFromTypeName($name);
        } catch (TypeException $exception) {
            throw CollectionException::typeIsNotValid($name, $exception);
        }
    }
}
