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

use precore\lang\Object;
use predaddy\messagehandling\annotation\AnnotatedMessageHandlerDescriptor;
use predaddy\messagehandling\DispatchInterceptor;
use predaddy\messagehandling\InterceptorChain;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Constraint validator based on Symfony component.
 *
 * Validates the incoming message and does one of the followings:
 *  - proceeds the chain if the message is valid
 *  - otherwise throws {@link ValidationException} which contains all violations
 *
 * @package predaddy\validation\symfony
 * @author Janos Szurovecz <szjani@szjani.hu>
 */
class ValidatorInterceptor extends Object implements DispatchInterceptor
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator = null)
    {
        if ($validator === null) {
            $validator = Validation::createValidatorBuilder()
                ->enableAnnotationMapping(AnnotatedMessageHandlerDescriptor::getReader())
                ->getValidator();
        }
        $this->validator = $validator;
    }

    public function invoke($message, InterceptorChain $chain)
    {
        $violations = $this->validator->validate($message);
        if ($violations->count() != 0) {
            self::getLogger()->debug("Constraint violation(s) occurred: [\n{}]", [$violations]);
            throw new ValidationException($violations);
        }
        $chain->proceed();
    }
}
