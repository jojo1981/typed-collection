<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace tests\Jojo1981\TypedCollection;

use Jojo1981\TypedCollection\CollectionIterator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Prophecy\Exception\Doubler\ClassNotFoundException;
use Prophecy\Exception\Doubler\DoubleException;
use Prophecy\Exception\Doubler\InterfaceNotFoundException;
use Prophecy\Exception\Prophecy\ObjectProphecyException;
use Prophecy\Prophecy\ObjectProphecy;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @package tests\Jojo1981\TypedCollection
 */
class CollectionIteratorTest extends TestCase
{
    /** @var ObjectProphecy|\ArrayIterator */
    private $arrayIterator;

    /**
     * @throws DoubleException
     * @throws InterfaceNotFoundException
     * @throws ClassNotFoundException
     * @return void
     */
    protected function setUp(): void
    {
        $this->arrayIterator = $this->prophesize(\ArrayIterator::class);
    }

    /**
     * @test
     *
     * @throws \BadMethodCallException
     * @throws ObjectProphecyException
     * @return void
     */
    public function offsetSetShouldThrowBadMethodCallException(): void
    {
        $this->expectExceptionObject(new \BadMethodCallException(
            'Don\'t use array access to add an element but use Jojo1981\TypedCollection\Collection::addElement'
        ));

        $this->getCollectionIterator()->offsetSet(4, 'text1');
    }

    /**
     * @test
     *
     * @throws \BadMethodCallException
     * @throws ObjectProphecyException
     * @return void
     */
    public function offsetUnsetShouldThrowBadMethodCallException(): void
    {
        $this->expectExceptionObject(new \BadMethodCallException(
            'Don\'t use array access to add an element but use Jojo1981\TypedCollection\Collection::removeElement'
        ));

        $this->getCollectionIterator()->offsetUnset(4);
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ObjectProphecyException
     * @return void
     */
    public function currentShouldReturnCurrentFromArrayIterator(): void
    {
        $this->arrayIterator->current()->willReturn('text')->shouldBeCalledOnce();
        $this->assertEquals('text', $this->getCollectionIterator()->current());
    }

    /**
     * @test
     *
     * @throws ObjectProphecyException
     * @return void
     */
    public function nextShouldCallNextOnArrayIterator(): void
    {
        $this->arrayIterator->next()->shouldBeCalledOnce();
        $this->getCollectionIterator()->next();
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ObjectProphecyException
     * @return void
     */
    public function keyShouldReturnKeyFromArrayIterator(): void
    {
        $this->arrayIterator->key()->willReturn('text')->shouldBeCalledOnce();
        $this->assertEquals('text', $this->getCollectionIterator()->key());
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ObjectProphecyException
     * @return void
     */
    public function validShouldReturnValidFromArrayIterator(): void
    {
        $this->arrayIterator->valid()->willReturn(false)->shouldBeCalledOnce();
        $this->assertFalse($this->getCollectionIterator()->valid());
    }

    /**
     * @test
     *
     * @throws ObjectProphecyException
     * @return void
     */
    public function rewindShouldCallRewindOnArrayIterator(): void
    {
        $this->arrayIterator->rewind()->shouldBeCalledOnce();
        $this->getCollectionIterator()->rewind();
    }

    /**
     * @test
     *
     * @throws ObjectProphecyException
     * @return void
     */
    public function seekShouldCallSeekOnArrayIterator(): void
    {
        $this->arrayIterator->seek(5)->shouldBeCalledOnce();
        $this->getCollectionIterator()->seek(5);
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ObjectProphecyException
     * @return void
     */
    public function offsetExistsShouldReturnOffsetExistsFromArrayIterator(): void
    {
        $this->arrayIterator->offsetExists('key')->willReturn(false)->shouldBeCalledOnce();
        $this->assertFalse($this->getCollectionIterator()->offsetExists('key'));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ObjectProphecyException
     * @return void
     */
    public function offsetGetShouldReturnOffsetGetFromArrayIterator(): void
    {
        $this->arrayIterator->offsetGet('value1')->willReturn(6)->shouldBeCalledOnce();
        $this->assertEquals(6, $this->getCollectionIterator()->offsetGet('value1'));
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ObjectProphecyException
     * @return void
     */
    public function countShouldReturnCountFromArrayIterator(): void
    {
        $this->arrayIterator->count()->willReturn(3)->shouldBeCalledOnce();
        $this->assertEquals(3, $this->getCollectionIterator()->count());
    }

    /**
     * @throws ObjectProphecyException
     * @return CollectionIterator
     */
    private function getCollectionIterator(): CollectionIterator
    {
        return new CollectionIterator($this->arrayIterator->reveal());
    }
}