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

use Jojo1981\TypedCollection\Value\Exception\ValueException;

/**
 * @package Jojo1981\TypedCollection\Value\Validation
 */
class ErrorValidationResult implements ValidationResultInterface
{
    /** @var string */
    private $message;

    /**
     * @param string $message
     * @throws ValueException
     */
    public function __construct(string $message)
    {
        if (empty($message)) {
            throw new ValueException('Message can not be empty for an error result value object');
        }
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}