<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection\TestSuite\Tests\Value\Validation;

use Jojo1981\TypedCollection\Value\Validation\SuccessValidationResult;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @package Jojo1981\TypedCollection\TestSuite\Tests\Value\Validation
 */
class SuccessValidationResultTest extends TestCase
{
    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isValidShouldReturnTrue(): void
    {
        $this->assertTrue((new SuccessValidationResult())->isValid());
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @return void
     */
    public function getMessageShouldReturnNull(): void
    {
        $this->assertNull((new SuccessValidationResult())->getMessage());
    }
}