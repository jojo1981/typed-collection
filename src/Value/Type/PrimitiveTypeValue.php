<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection\Value\Type;

/**
 * This value class represent primitive types. Also the pseudo types: double, number will be accept but considered as a
 * float. The short values for a boolean and integer (`bool` and `int`) will be accepted, but transformed into the long
 * notation.
 *
 * @internal
 * @package Jojo1981\TypedCollection\Value\Type
 */
final class PrimitiveTypeValue extends AbstractTypeValue
{
    public const VALUE_INT = 'int';
    public const VALUE_INTEGER = 'integer';
    public const VALUE_FLOAT = 'float';
    public const VALUE_DOUBLE = 'double';
    public const VALUE_NUMBER = 'number';
    public const VALUE_BOOL = 'bool';
    public const VALUE_BOOLEAN = 'boolean';
    public const VALUE_STRING = 'string';
    public const VALUE_ARRAY = 'array';
    public const VALUE_OBJECT = 'object';

    /** @var string[] */
    private const VALID_VALUES = [
        self::VALUE_INT,
        self::VALUE_INTEGER,
        self::VALUE_FLOAT,
        self::VALUE_DOUBLE,
        self::VALUE_NUMBER,
        self::VALUE_BOOL,
        self::VALUE_BOOLEAN,
        self::VALUE_STRING,
        self::VALUE_ARRAY,
        self::VALUE_OBJECT
    ];

    /** @var string[] */
    private const VALUE_MAP = [
        self::VALUE_BOOL => self::VALUE_BOOLEAN,
        self::VALUE_INT => self::VALUE_INTEGER,
        self::VALUE_NUMBER => self::VALUE_FLOAT,
        self::VALUE_DOUBLE => self::VALUE_FLOAT
    ];

    /**
     * @param TypeValueInterface $otherTypeValue
     * @return bool
     */
    public function match(TypeValueInterface $otherTypeValue): bool
    {
        return $this->isEqual($otherTypeValue);
    }

    /**
     * @param string $type
     * @return string
     */
    protected function mapValue(string $type): string
    {
        $type = \strtolower($type);
        if (\array_key_exists($type, static::VALUE_MAP)) {
            return static::VALUE_MAP[$type];
        }

        return $type;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function getExceptionMessage(string $value): string
    {
        return \sprintf(
            'Invalid type: `%s` given. Valid types are [%s]',
            $value,
            \implode(', ', static::getValidValues())
        );
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isValidValue(string $value): bool
    {
        return \in_array(\strtolower($value), static::VALID_VALUES, true);
    }

    /**
     * @return string[]
     */
    public static function getValidValues(): array
    {
        return static::VALID_VALUES;
    }
}