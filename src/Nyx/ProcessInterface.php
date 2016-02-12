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

interface ProcessInterface
{
    /**
     * Set the command
     *
     * @param  CommandInterface $command
     * @return void
     */
    public function setCommand(CommandInterface $command);

    /**
     * Return the command
     *
     * @return CommandInterface
     */
    public function getCommand();

    /**
     * Starts the process.
     *
     * @return mixed
     */
    public function open();

    /**
     * Close the process
     *
     * @return void
     */
    public function close();

    /**
     * Determine whether the process is running
     *
     * @return bool
     */
    public function isRunning();

    /**
     * Return an array of errors
     *
     * @return array
     */
    public function errors();

    /**
     * @return mixed
     */
    public function status();
}
