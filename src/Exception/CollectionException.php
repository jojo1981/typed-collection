<?php declare(strict_types=1);
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection\Exception;

use DomainException;
use Jojo1981\PhpTypes\ClassType;
use Jojo1981\PhpTypes\TypeInterface;
use Throwable;
use function sprintf;

/**
 * This is the base exception class for this library an all other exceptions thrown by this library extends from this
 * exceptions.
 *
 * @api
 * @package Jojo1981\TypedCollection\Exception
 */
final class CollectionException extends DomainException
{
    /**
     * Private constructor, prevent getting an instance of this class using the new keyword from outside the lexical scope of this class.
     *
     * @param string $message
     * @param Throwable|null $previous
     */
    private function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return self
     */
    public static function noSuchElement(): self
    {
        return new self('Element does not exists in this collection');
    }

    /**
     * @return self
     */
    public static function couldNotSortCollection(): self
    {
        return new self('Could not sort the collection');
    }

    /**
     * @param TypeInterface $expectedType
     * @param TypeInterface $actualType
     * @return self
     */
    public static function dataOfInvalidTypeGiven(TypeInterface $expectedType, TypeInterface $actualType): self
    {
        return new self((sprintf(
            'Data is not %s: `%s`, but %s: `%s`',
            $expectedType instanceof ClassType ? 'an instance of' : 'of expected type',
            $expectedType->getName(),
            $actualType instanceof ClassType ? 'an instance of' : 'of type',
            $actualType->getName()
        )));
    }

    /**
     * @return self
     */
    public static function emptyCollection(): self
    {
        return new self('An empty array with typed collections passed');
    }

    /**
     * @return self
     */
    public static function notEnoughCollections(): self
    {
        return new self('At least 2 collections needs to be passed');
    }

    /**
     * @return self
     */
    public static function invalidCollectionsData(): self
    {
        return new self('Expect $collections array to contain instances of Collection');
    }

    /**
     * @param TypeInterface $expectedType
     * @param TypeInterface $actualType
     * @return self
     */
    public static function collectionNotAllOfSameType(TypeInterface $expectedType, TypeInterface $actualType): self
    {
        return new self(sprintf(
            'Expect every collection to be of type: `%s`. Collection found with type: `%s`',
            $expectedType->getName(),
            $actualType->getName()
        ));
    }

    /**
     * @param string $type
     * @param Throwable|null $previous
     * @return self
     */
    public static function typeIsNotValid(string $type, ?Throwable $previous = null): self
    {
        return new self('Given type: `' . $type . '` is not a valid primitive type and also not an existing class', $previous);
    }

    /**
     * @return self
     */
    public static function emptyElementsCanNotDetermineType(): self
    {
        return new self('Elements can not be empty, because type can NOT be determined');
    }

    /**
     * @param Throwable $previous
     * @return self
     */
    public static function couldNotCreateTypeFromValue(Throwable $previous): self
    {
        return new self('Could not create type from value', $previous);
    }

    /**
     * @param string $type
     * @param string $otherType
     * @return self
     */
    public static function couldNotMergeCollection(string $type, string $otherType): self
    {
        return new self(sprintf(
            'Can not merge typed collections with different types. This collection is of ' .
            'type: `%s` and the other collection of type: `%s`',
            $type,
            $otherType
        ));
    }
}
