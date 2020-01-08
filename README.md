A typed collection data structure
=====================

[![Build Status](https://travis-ci.com/jojo1981/typed-collection.svg?branch=master)](https://travis-ci.com/jojo1981/typed-collection)
[![Coverage Status](https://coveralls.io/repos/github/jojo1981/typed-collection/badge.svg)](https://coveralls.io/github/jojo1981/typed-collection)
[![Latest Stable Version](https://poser.pugx.org/jojo1981/typed-collection/v/stable)](https://packagist.org/packages/jojo1981/typed-collection)
[![Total Downloads](https://poser.pugx.org/jojo1981/typed-collection/downloads)](https://packagist.org/packages/jojo1981/typed-collection)
[![License](https://poser.pugx.org/jojo1981/typed-collection/license)](https://packagist.org/packages/jojo1981/typed-collection)

Author: Joost Nijhuis <[jnijhuis81@gmail.com](mailto:jnijhuis81@gmail.com)>

The typed collection is an ordered mutable sequence.  

It is a data structure which is in fact a wrapper around an indexed array.  
When a new collection is created the `type` for the collection *MUST* be given. 
The collection is of a certain `type` and will guarantee all elements in the collection are of the same `type`.  
The *type* can be a **primitive** `type` or a **class**/**interface** `type` set for the collection.

Available types are:

- int (alias integer),
- float (aliases real, double or number)
- string (alias text)
- array
- object
- callable (alias callback)
- iterable
- class (class or interface name)

The `\Jojo1981\TypedCollection\Collection` class is countable and is traversable (iterable).
The collection has the following convenient instance methods:

- getType(): string
- isEqualType(Collection $otherCollection): bool
- isEmpty(): bool
- isNonEmpty(): bool
- unshiftElement($element): void
- pushElement($element): void
- shiftElement()
- popElement()
- hasElement($element): bool
- pushElements(array $elements): void
- unshiftElements(array $elements): void
- setElements(array $elements): void
- removeElement($element): void
- indexOfElement($element): ?int
- getElementByIndex(int $index)
- getFirstElement()
- getLastElement()
- getFirstElementAsCollection(): Collection
- toArray(): array
- sortBy(callable $comparator): Collection
- map(string $type, callable $mapper): Collection
- flatMap(string $type, callable $mapper): Collection
- merge(Collection $otherCollection, Collection ...$otherCollections): void
- all(callable $predicate): bool
- some(callable $predicate): bool
- none(callable $predicate): bool
- forEach(callable $callback): void
- foldLeft(callable $callback, $initial = null)
- foldRight(callable $callback, $initial = null)
- filter(callable $predicate): Collection
- find(callable $predicate)
- group(callable $predicate, callable ...$predicates): Collection
- partition(callable $predicate): Collection
- slice(int $offset, ?int $length = null): Collection
- clear(): void
- count(): int
- getIterator(): CollectionIterator
- isEqualCollection(Collection $otherCollection, ?callable $predicate = null, bool $strict = false): bool

The `\Jojo1981\TypedCollection\Collection` has a static method `createFromCollections`.   
Multiple collection of the same `type` can be merged together into one collection.
 
## Installation

### Library

```bash
git clone https://github.com/jojo1981/typed-collection.git
```

### Composer

[Install PHP Composer](https://getcomposer.org/doc/00-intro.md)

```bash
composer require jojo1981/typed-collection
```

## Basic usage

```php
<?php

require 'vendor/autoload.php';

// Create an empty collection of type `string`
$collection1 = new \Jojo1981\TypedCollection\Collection('string');

// Create a collection of type `integer` with some elements
$collection2 = new \Jojo1981\TypedCollection\Collection('int', [1, 2, 3]);

// Will throw an exception, because an invalid type has been given
try {
    $collection = new \Jojo1981\TypedCollection\Collection('invalid');
} catch (\Jojo1981\TypedCollection\Exception\CollectionException $exception) {
  echo $exception->getMessage() . PHP_EOL;
}

// Create a collection of class type \stdClass
$collection3 = new \Jojo1981\TypedCollection\Collection(\stdClass::class);
$collection3->pushElement(new \stdClass());

try {
    $collection3->pushElement('element');
} catch (\Jojo1981\TypedCollection\Exception\CollectionException $exception) {
    echo $exception->getMessage() . PHP_EOL;
}

echo 'Collection count: ' . $collection3->count() . PHP_EOL; // Will be 1
```