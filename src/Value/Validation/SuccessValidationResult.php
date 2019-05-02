<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection\Value\Validation;

/**
 * @package Jojo1981\TypedCollection\Value\Validation
 */
class SuccessValidationResult implements ValidationResultInterface
{
    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return true;
    }

    /**
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return null;
    }
}