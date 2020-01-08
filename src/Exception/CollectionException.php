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

/**
 * This is the base exception class for this library an all other exceptions thrown by this library extends from this
 * exceptions.
 *
 * @api
 * @package Jojo1981\TypedCollection\Exception
 */
class CollectionException extends \DomainException
{
    /**
     * @param string $type
     * @param null|\Exception $previous
     * @return CollectionException
     */
    public static function typeIsNotValid(string $type, ?\Exception $previous = null): CollectionException
    {
        return new self(
            'Given type: `' . $type . '` is not a valid primitive type and also not an existing class',
            0,
            $previous
        );
    }

    /**
     * @param string $type
     * @param string $otherType
     * @return CollectionException
     */
    public static function couldNotMergeCollection(string $type, string $otherType): CollectionException
    {
        return new self(\sprintf(
            'Can not merge typed collections with different types. This collection is of ' .
            'type: `%s` and the other collection of type: `%s`',
            $type,
            $otherType
        ));
    }

    /**
     * @return CollectionException
     */
    public static function emptyElementsCanNotDetermineType(): CollectionException
    {
        return new self('Elements can not be empty, because type can NOT be determined');
    }

    /**
     * @param \Exception $previous
     * @return CollectionException
     */
    public static function couldNotCreateTypeFromValue(\Exception $previous): CollectionException
    {
        return new self('Could not create type from value', 0, $previous);
    }
}
