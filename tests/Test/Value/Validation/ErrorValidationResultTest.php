<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace Jojo1981\TypedCollection\TestSuite\Test\Value\Validation;

use Jojo1981\TypedCollection\Value\Exception\ValueException;
use Jojo1981\TypedCollection\Value\Validation\ErrorValidationResult;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @package Jojo1981\TypedCollection\TestSuite\Test\Value\Validation
 */
class ErrorValidationResultTest extends TestCase
{
    /**
     * @test
     *
     * @throws ValueException
     * @return void
     */
    public function constructWithEmptyMessageShouldThrowValueException(): void
    {
        $this->expectExceptionObject(new ValueException('Message can not be empty for an error result value object'));
        new ErrorValidationResult('');
    }

    /**
     * @test
     *
     * @throws InvalidArgumentException
     * @throws ValueException
     * @throws ExpectationFailedException
     * @return void
     */
    public function isValidShouldReturnFalse(): void
    {
        $this->assertFalse((new ErrorValidationResult('A message'))->isValid());
    }

    /**
     * @test
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ValueException
     * @return void
     */
    public function getMessageShouldReturnTheCorrectMessage(): void
    {
        $this->assertEquals('test message 1', (new ErrorValidationResult('test message 1'))->getMessage());
        $this->assertEquals('test message 2', (new ErrorValidationResult('test message 2'))->getMessage());
    }
}