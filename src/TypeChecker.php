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
final class TypeChecker
{
    /** @var string[] */
    private const TYPE_MAP = [
        'bool' => 'boolean',
        'int' => 'integer',
        'double' => 'float',
        'number' => 'float'
    ];

    /**
     * Private constructor to prevent getting an instance of this class
     *
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        // nothing to do here
    }

    /**
     * @param mixed $data
     * @param string $expectedType
     * @return bool
     */
    public static function isDataOfExpectedType($data, string $expectedType): bool
    {
        if (\class_exists($expectedType) || \interface_exists($expectedType)) {
            return $data instanceof $expectedType;
        }

        $expectedType = static::normalizePrimitiveType($expectedType);
        $actualType = static::normalizePrimitiveType(\gettype($data));

        return $actualType === $expectedType;
    }

    /**
     * @param string $type
     * @return string
     */
    private static function normalizePrimitiveType(string $type): string
    {
        $type = \strtolower($type);
        if (\array_key_exists($type, static::TYPE_MAP)) {
            return static::TYPE_MAP[$type];
        }

        return $type;
    }
}