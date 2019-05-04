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
 * This interface describes an interface for a validation result. This can be a success or error validation result.
 *
 * @internal
 * @package Jojo1981\TypedCollection\Value\Validation
 */
interface ValidationResultInterface
{
    /**
     * Return true when the validation was successful otherwise false should be returned.
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * When the validation was not successful a message with detailed information can be optionally available.
     * This method return the string message or null when it was not set.
     *
     * @return null|string
     */
    public function getMessage(): ?string;
}