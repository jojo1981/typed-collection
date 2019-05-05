<?php
/*
 * This file is part of the jojo1981/typed-collection package
 *
 * Copyright (c) 2019 Joost Nijhuis <jnijhuis81@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed in the root of the source code
 */
namespace tests\Jojo1981\TypedCollection\Tests\Value\Validation;

use Jojo1981\TypedCollection\Value\Exception\ValueException;
use Jojo1981\TypedCollection\Value\Validation\ErrorValidationResult;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @package tests\Jojo1981\TypedCollection\Tests\Value\Validation
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
     * @dataProvider getTestData
     *
     * @param string $message
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ValueException
     * @return void
     */
    public function getMessageShouldReturnTheCorrectMessage(string $message): void
    {
        $this->assertEquals($message, (new ErrorValidationResult($message))->getMessage());
    }

    /**
     * @return array[]
     */
    public function getTestData(): array
    {
        return [['test message 1'], ['test message 2']];
    }
}