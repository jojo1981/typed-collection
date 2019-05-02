<?php
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
 * @package Jojo1981\TypedCollection\Exception
 */
class CollectionException extends \DomainException
{
    /**
     * @param string $type
     * @return CollectionException
     */
    public static function typeIsNotValid(string $type): CollectionException
    {
        return new self('Given type: `' . $type . '` is not a valid primitive type and also not an existing class');
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
}
