<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection\Metadata;

/**
 * @package Jojo1981\TypedCollection\Metadata
 */
final class TypeMetadata
{
    /** @var string[] */
    private const TYPE_MAP = [
        'double' => 'float'
    ];

    /** @var bool */
    private $isPrimitiveType;

    /** @var string */
    private $type;

    /**
     * @param mixed $data
     */
    public function __construct($data)
    {
        if (\is_object($data) && false !== $className = \get_class($data)) {
            $this->isPrimitiveType = false;
            $this->type = $className;
        } else {
            $this->isPrimitiveType = true;
            $this->type = $this->mapPrimitiveType(\gettype($data));
        }
    }

    /**
     * @return bool
     */
    public function isPrimitiveType(): bool
    {
        return true === $this->isPrimitiveType;
    }

    /**
     * @return bool
     */
    public function isClassType(): bool
    {
        return false === $this->isPrimitiveType;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return string
     */
    private function mapPrimitiveType(string $type): string
    {
        $type = \strtolower($type);
        if (\array_key_exists($type, static::TYPE_MAP)) {
            return static::TYPE_MAP[$type];
        }

        return $type;
    }
}