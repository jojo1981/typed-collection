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

use Jojo1981\TypedCollection\TypeChecker;
use Jojo1981\TypedCollection\Value\Exception\ValueException;
use Jojo1981\TypedCollection\Value\Validation\ErrorValidationResult;
use Jojo1981\TypedCollection\Value\Validation\SuccessValidationResult;
use Jojo1981\TypedCollection\Value\Validation\ValidationResultInterface;

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
    final public function isEqual(TypeValueInterface $otherTypeValue): bool
    {
        return TypeChecker::isExactlyTheSameClassType($this, $otherTypeValue)
            && $this->getValue() === $otherTypeValue->getValue();
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
     * @param mixed $data
     * @throws ValueException
     * @return ValidationResultInterface
     */
    final public function isValidData($data): ValidationResultInterface
    {
        if (TypeChecker::isDataOfExpectedType($data, $this->getValue())) {
            return new SuccessValidationResult();
        }

        return new ErrorValidationResult(\sprintf(
            'Data is not %s: `%s`, but %s: `%s`',
            $this instanceof ClassNameTypeValue ? 'an instance of' : 'of expected type',
            $this->getValue(),
            TypeChecker::isClassType($data) ? 'an instance of' : 'of type',
            TypeChecker::getType($data)
        ));
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
     * Factory method
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

        throw new ValueException(\sprintf('Could not create a type value instance based on value: `%s`', $value));
    }
}