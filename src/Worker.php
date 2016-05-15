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

class Worker implements WorkerInterface, OutputableInterface
{
    /**
     * @var ProcessInterface
     */
    protected $process;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var bool
     */
    protected $started = false;

    /**
     * Worker constructor.
     *
     * @param ProcessInterface $process
     */
    public function __construct(ProcessInterface $process)
    {
        $this->process = $process;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        if ($this->started === false) {
            $this->getOutput()->write("[+] Starting worker");

            $this->started = true;
            $this->process->open();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    public function getOutput()
    {
        if (is_null($this->output)) {
            $this->setOutput(new Console());
        }

        return $this->output;
    }
}
