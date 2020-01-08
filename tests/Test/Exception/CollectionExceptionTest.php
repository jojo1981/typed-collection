<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection\TestSuite\Test\Exception;

use Jojo1981\TypedCollection\Exception\CollectionException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @package Jojo1981\TypedCollection\TestSuite\Test\Exception
 */
class CollectionExceptionTest extends TestCase
{
    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function typeIsNotValidShouldReturnCollectionException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = CollectionException::typeIsNotValid('invalidType', $previous);
        $this->assertEquals(
            'Given type: `invalidType` is not a valid primitive type and also not an existing class',
            $exception->getMessage()
        );
        $this->assertSame($previous, $exception->getPrevious());
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function couldNotMergeCollectionShouldReturnCollectionException(): void
    {
        $exception = CollectionException::couldNotMergeCollection('actualType', 'expectedType');
        $this->assertEquals(
            'Can not merge typed collections with different types. This collection is of ' .
            'type: `actualType` and the other collection of type: `expectedType`',
            $exception->getMessage()
        );
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function emptyElementsCanNotDetermineTypeShouldReturnCollectionException(): void
    {
        $exception = CollectionException::emptyElementsCanNotDetermineType();
        $this->assertEquals(
            'Elements can not be empty, because type can NOT be determined',
            $exception->getMessage()
        );
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function couldNotCreateTypeFromValueShouldReturnCollectionException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = CollectionException::couldNotCreateTypeFromValue($previous);
        $this->assertEquals(
            'Could not create type from value',
            $exception->getMessage()
        );
        $this->assertSame($previous, $exception->getPrevious());
    }
}