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

use ArrayAccess;
use ArrayIterator;
use BadMethodCallException;
use Countable;
use SeekableIterator;

/**
 * @api
 * @package Jojo1981\TypedCollection
 */
final class CollectionIterator implements SeekableIterator, ArrayAccess, Countable
{
    /** @var ArrayIterator */
    private ArrayIterator $arrayIterator;

    /**
     * @param ArrayIterator $arrayIterator
     */
    public function __construct(ArrayIterator $arrayIterator)
    {
        $this->arrayIterator = $arrayIterator;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws BadMethodCallException
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new BadMethodCallException(
            'Don\'t use array access to add an element but use ' . Collection::class . '::addElement'
        );
    }

    /**
     * @param mixed $offset
     * @throws BadMethodCallException
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new BadMethodCallException(
            'Don\'t use array access to add an element but use ' . Collection::class . '::removeElement'
        );
    }

    /**
     * @return mixed
     */
    public function current(): mixed
    {
        return $this->arrayIterator->current();
    }

    /**
     * @return void
     */
    public function next(): void
    {
        $this->arrayIterator->next();
    }

    /**
     * @return string|int|null
     */
    public function key(): string|int|null
    {
        return $this->arrayIterator->key();
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->arrayIterator->valid();
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->arrayIterator->rewind();
    }

    /**
     * @param int $offset
     * @return void
     */
    public function seek(int $offset): void
    {
        $this->arrayIterator->seek($offset);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->arrayIterator->offsetExists($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->arrayIterator->offsetGet($offset);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->arrayIterator->count();
    }
}
