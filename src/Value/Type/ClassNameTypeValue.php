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
 * @package Jojo1981\TypedCollection\Value\Type
 */
final class ClassNameTypeValue extends AbstractTypeValue
{
    /**
     * @param string $value
     * @return string
     */
    protected function getExceptionMessage(string $value): string
    {
        if (empty($value)) {
            return 'Value can not be empty';
        }
        if (\class_exists($value)) {
            return \sprintf('Invalid existing class name: `%s` it is not instantiable', $value);
        }

        return \sprintf('Invalid class name: `%s`', $value);

    }

    /**
     * @param string $value
     * @return string
     */
    protected function mapValue(string $value): string
    {
        if (0 === \strpos($value, '\\')) {
            return \substr($value, 1);
        }

        return $value;
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function isValidData($data): bool
    {
        if (\is_object($data) && false !== $className = \get_class($data)) {
            return $this->getValue() === $className;
        }

        return false;
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isValidValue(string $value): bool
    {
        try {
            return (new \ReflectionClass($value))->isInstantiable();
        } catch (\ReflectionException $exception) {
            return false;
        }
    }
}