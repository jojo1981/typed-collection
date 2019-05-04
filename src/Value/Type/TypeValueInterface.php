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
use Jojo1981\TypedCollection\Value\Validation\ValidationResultInterface;

/**
 * This interface describes a uniform interface to for types which can be primitive or class/interface types.
 * The concrete classes which implement this interface should be immutable. Only the type string value may be set at
 * construction type.
 *
 * @internal
 * @package Jojo1981\TypedCollection\Value\Type
 */
interface TypeValueInterface
{
    /**
     * Construct a new type value based on the given string type. When the given string value is not valid
     * a `ValueException` should be thrown.
     *
     * @param string $value
     * @throws ValueException
     */
    public function __construct(string $value);

    /**
     * This method should return true when the type are exactly matching. This means that both concrete class instances
     * which implement this interface `TypeValueInterface` should be of exactly the same class and the string value must
     * be exactly hte same. They both may have difference references.
     * When this method is reversed an called on the other type and give this type as parameter the result should always
     * be the same.
     *
     * @param TypeValueInterface $otherTypeValue
     * @return bool
     */
    public function isEqual(TypeValueInterface $otherTypeValue): bool;

    /**
     * This method should return true when the types are matching, but this logic is specific for the concrete class which
     * implement this interface: `TypeValueInterface`.
     * The matching logic should be to compare the given other type value against your own type.
     * The types should both match exactly the same class type (not required to be the same instance), but can have other
     * type string values.
     *
     * For example:
     *
     *  - a PrimitiveTypeValue with value 'string' compared to the other type which is also PrimitiveTypeValue with also
     *    value 'string' should match and return true. (in this case it works the same as TypeValueInterface::isEqual
     *  - a ClassNameTypeValue type with value `MyNamespace\MyNiceInterface' compared to the other type which is also a
     *    ClassNameTypeValue and has a different value `MyNamespace\MyNiceImplementationClass` should match when the
     *    `MyNamespace\MyNiceImplementationClass' is an instance of `MyNamespace\MyNiceInterface' and return true.
     *    When reverse this an call match on the other type and give this type as parameter the result should be false,
     *    because  `MyNamespace\MyNiceInterface' is NOT an instance of `MyNamespace\MyNiceImplementationClass'.
     *
     * @param TypeValueInterface $otherTypeValue
     * @return bool
     */
    public function match(TypeValueInterface $otherTypeValue): bool;

    /**
     * Should return the internal string value which represent the type.
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Should return the string presentation for the type and should probably return the same result as the `getValue`
     * method does. This is not required.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Check if the the passed data which can be of any type is valid for this type. Return a `ValidationResultInterface`
     * which can be a `SuccessValidationResult` or an `ErrorValidationResult`.
     * When an ErrorValidationResult is returned. Some detailed information about why the data is not valid for this type
     * can be gotten form the `ErrorValidationResult` instance.
     *
     * @param mixed $data
     * @return ValidationResultInterface
     */
    public function isValidData($data): ValidationResultInterface;

    /**
     * Check if the string value is valid for this type. Can be used to first check if the value if valid before creating
     * a type instance which will throw a `ValueException` when the value string is not valid.
     *
     * @param string $value
     * @return bool
     */
    public static function isValidValue(string $value): bool;
}