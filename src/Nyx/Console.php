<?php
/**
 * Nyx
 *
 * (The MIT license)
 * Copyright (c) 2016 Luke Steadman
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated * documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @package Nyx
 */
namespace Nyx;

class Console implements OutputInterface
{
    /**
     * Array of messages
     *
     * @var array
     */
    protected $buffer = array();

    /**
     * Write the message.
     *
     * @param string $msg
     * @return string
     */
    public function write($msg)
    {
        print($msg) . PHP_EOL;
    }

    /**
     * Clear the buffer.
     *
     * $write allows the developer to flush the buffer without outputting.
     *
     * @param bool $write
     * @return mixed
     */
    public function flush($write = true)
    {
        if(!$write) {
            $this->buffer = array();
        }

        foreach((array)$this->buffer as $msg) {
            $this->write($msg);
        }
    }

    /**
     * Buffer a message ready for output
     *
     * @param string $msg
     * @return $this
     */
    public function buffer($msg)
    {
        if(is_string($msg)) {
            $this->buffer[] = $msg;
        }
    }
}
