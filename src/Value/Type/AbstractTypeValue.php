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

use Jojo1981\TypedCollection\Value\Exception\ValueException;

/**
 * @package Jojo1981\TypedCollection\Value\Type
 */
abstract class AbstractTypeValue implements TypeValueInterface
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     * @throws ValueException
     */
    final public function __construct(string $value)
    {
        $this->assertValue($value);
        $this->value = $this->mapValue($value);
    }

    /**
     * @param TypeValueInterface $otherTypeValue
     * @return bool
     */
    final public function match(TypeValueInterface $otherTypeValue): bool
    {
        return \get_class($otherTypeValue) === \get_class($this) && $this->getValue() === $otherTypeValue->getValue();
    }

    /**
     * @return string
     */
    final public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    final public function __toString(): string
    {
        return $this->getValue();
    }


    /**
     * @param string $value
     * @throws ValueException
     * @return void
     */
    private function assertValue(string $value): void
    {
        if (!static::isValidValue($value)) {
            throw new ValueException($this->getExceptionMessage($value));
        }
    }

    /**
     * @param string $value
     * @return string
     */
    abstract protected function mapValue(string $value): string;

    /**
     * @param string $value
     * @return string
     */
    abstract protected function getExceptionMessage(string $value): string;

    /**
     * @param string $value
     * @return bool
     */
    public static function isValidValue(string $value): bool
    {
        return PrimitiveTypeValue::isValidValue($value) || ClassNameTypeValue::isValidValue($value);
    }

    /**
     * Factory method design pattern
     *
     * @param string $value
     * @throws ValueException
     * @return TypeValueInterface
     */
    final public static function createTypeValueInstance(string $value): TypeValueInterface
    {
        if (PrimitiveTypeValue::isValidValue($value)) {
            return new PrimitiveTypeValue($value);
        }
        if (ClassNameTypeValue::isValidValue($value)) {
            return new ClassNameTypeValue($value);
        }

        throw new ValueException(\sprintf(
            'Could not create a type value instance instance based on value: `%s`',
            $value
        ));
    }
}