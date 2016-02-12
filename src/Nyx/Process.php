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

class Process implements ProcessInterface, OutputableInterface
{
    const PROCESS_STOPPED = 0;
    const PROCESS_RUNNING = 1;
    const PROCESS_SIGNALED = 2;

    /**
     * @var resource
     */
    protected $process;

    /**
     * @var CommandInterface
     */
    protected $command;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Process constructor.
     *
     * @param CommandInterface $command
     */
    public function __construct(CommandInterface $command)
    {
        $this->command = $command;
    }

    /**
     * {@inheritdoc}
     */
    public function setCommand(CommandInterface $command)
    {
        $this->command = $command;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * {@inheritdoc}
     */
    public function open()
    {
        $command = $this->command->toString();
        $this->getOutput()->write("[+] New process with command: " . $command);

        // Command options
        $in  = array_values($this->command->getOption('in', array('pipe', 'r')));
        $out = array_values($this->command->getOption('out', array('file', '/tmp/nyx.log', 'a')));
        $err = array_values($this->command->getOption('err', array('file', '/tmp/nyx-error.log', 'a')));

        $this->process = proc_open($command, array(
            0 => $in,
            1 => $out,
            2 => $err
        ), $pipes);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if (is_resource($this->process)) {
            proc_close($this->process);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isRunning()
    {
        if (is_resource($this->process)) {
            $status = proc_get_status($this->process);
            return array_key_exists('running', $status) && (bool)$status['running'];
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function errors()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function status()
    {
        if ($this->isRunning()) {
            return static::PROCESS_RUNNING;
        }

        if (is_resource($this->process)) {
            $status = proc_get_status($this->process);

            if ($status['stopped'] === true) {
                return static::PROCESS_STOPPED;
            }
        }

        return static::PROCESS_SIGNALED;
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
            $this->output = new Console();
        }

        return $this->output;
    }
}
