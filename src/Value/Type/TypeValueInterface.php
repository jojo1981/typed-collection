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
interface TypeValueInterface
{
    /**
     * @param string $value
     * @throws ValueException
     */
    public function __construct(string $value);

    /**
     * @param TypeValueInterface $otherTypeValue
     * @return bool
     */
    public function match(TypeValueInterface $otherTypeValue): bool;

    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @param mixed $data
     * @return bool
     */
    public function isValidData($data): bool;

    /**
     * @param string $value
     * @return bool
     */
    public static function isValidValue(string $value): bool;
}