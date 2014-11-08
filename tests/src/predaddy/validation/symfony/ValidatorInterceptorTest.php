<?php
/*
 * Copyright (c) 2014 Janos Szurovecz
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace predaddy\validation\symfony;

use PHPUnit_Framework_TestCase;
use predaddy\messagehandling\MessageBus;
use predaddy\messagehandling\SimpleMessageBus;

/**
 * Class ValidatorInterceptorTest
 *
 * @package predaddy\validation\symfony
 * @author Janos Szurovecz <szjani@szjani.hu>
 */
class ValidatorInterceptorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MessageBus
     */
    private $bus;

    protected function setUp()
    {
        $this->bus = SimpleMessageBus::builder()
            ->withInterceptors([new ValidatorInterceptor()])
            ->build();
    }

    /**
     * @test
     * @expectedException \predaddy\validation\symfony\ValidationException
     */
    public function shouldThrowExceptionIfMessageIsInvalid()
    {
        $this->bus->post(new CreateUser('', 'john@example.com'));
    }

    /**
     * @test
     */
    public function shouldPassValidObject()
    {
        $called = false;
        $this->bus->registerClosure(
            function (CreateUser $user) use (&$called) {
                $called = true;
            }
        );
        $this->bus->post(new CreateUser('John Doe', 'john@gmail.com'));
        self::assertTrue($called);
    }
}
