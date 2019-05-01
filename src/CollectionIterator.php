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

/**
 * @package Jojo1981\TypedCollection
 */
class CollectionIterator implements \SeekableIterator, \ArrayAccess, \Countable
{
    /** @var \ArrayIterator */
    private $arrayIterator;

    /**
     * @param \ArrayIterator $arrayIterator
     */
    public function __construct(\ArrayIterator $arrayIterator)
    {
        $this->arrayIterator = $arrayIterator;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws \BadMethodCallException
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        throw new \BadMethodCallException(
            'Don\'t use array access to add an element but use ' . Collection::class . '::addElement'
        );
    }

    /**
     * @param mixed $offset
     * @throws \BadMethodCallException
     * @return void
     */
    public function offsetUnset($offset): void
    {
        throw new \BadMethodCallException(
            'Don\'t use array access to add an element but use ' . Collection::class . '::removeElement'
        );
    }

    /**
     * @return mixed
     */
    public function current()
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
     * @return mixed
     */
    public function key()
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
     * @param int $position
     * @return void
     */
    public function seek($position): void
    {
        $this->arrayIterator->seek($position);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->arrayIterator->offsetExists($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
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
