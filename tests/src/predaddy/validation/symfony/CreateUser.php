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

use precore\util\Objects;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CreateUser
 *
 * @package predaddy\validation\symfony
 * @author Janos Szurovecz <szjani@szjani.hu>
 */
class CreateUser
{
    /**
     * @Assert\Length(min = 3)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @Assert\Email
     * @Assert\NotBlank
     */
    private $email;

    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * @Assert\True(message = "The user should have a Google Mail account")
     */
    public function isGmailUser()
    {
        return false !== strpos($this->email, '@gmail.com');
    }

    public function __toString()
    {
        return Objects::toStringHelper($this)
            ->add('name', $this->name)
            ->add('email', $this->email)
            ->toString();
    }
}
