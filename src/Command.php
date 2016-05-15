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

class Command implements CommandInterface
{
    /**
     * Command string
     *
     * @var
     */
    protected $cmd;

    /**
     * Array of options
     *
     * @var array
     */
    protected $options = array();

    /**
     * Command constructor.
     *
     * @param  $cmd
     * @param  array|null $options
     * @throws \Exception
     */
    public function __construct($cmd, array $options = null)
    {
        if (!is_string($cmd)) {
            throw new \Exception('$cmd must be a string');
        }

        $this->cmd = $cmd;
        $this->options = (array) $options;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return (string) $this->cmd;
    }

    /**
     * {@inheritdoc}
     */
    public function getCmd()
    {
        return $this->cmd;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($option, $default = null)
    {
        if (array_key_exists($option, $this->options)) {
            return $this->options[$option];
        }

        return $default;
    }
}
