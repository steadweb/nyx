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

final class Handler
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;

        pcntl_signal(SIGINT, array($this, "catchSignal"));
        pcntl_signal(SIGTERM, array($this, "catchSignal"));
        pcntl_signal(SIGHUP, array($this, "catchSignal"));
        pcntl_signal(SIGUSR1, array($this, "catchSignal"));

        $this->manager->getOutput()->write('[*] Handler registered');
    }

    /**
     * @param $signal
     */
    public function catchSignal($signal)
    {
        switch ($signal) {
            case SIGINT:
            case SIGTERM:
                $this->manager->getOutput()->write('[-] Exiting.');
                exit(1);
        }
    }
}