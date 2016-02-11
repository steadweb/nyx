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

use Noodlehaus\Config;

final class Manager implements OutputableInterface
{
    /**
     * @string
     */
    const VERSION = '0.1.0';

    /**
     * @string
     */
    const NAME = 'Nyx - Process Manager';

    /**
     * @var
     */
    protected $pools;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var Handler
     */
    protected $handler;

    /**
     * Create a Manager instance using config
     *
     * @param $path
     * @return static
     * @throws \Exception
     */
    public static function factory($path)
    {
        $pools = array();

        try {
            if(file_exists($path)) {
                $config = Config::load($path);

                foreach($config->get('workers', array()) as $worker) {
                    $count = array_key_exists('count', $worker) ? $worker['count'] : null;
                    $options = array_key_exists('options', $worker) ? $worker['options'] : array();

                    $pool = new Pool($count);
                    $pool->add(new Worker(new Process(new Command($worker['path'], $options))));
                    $pools[] = $pool;
                }
            }
        } catch(\Exception $e) {
            throw $e;
        }

        $instance = new static($pools);

        return $instance;
    }

    /**
     * Manager constructor
     *
     * @param array $pools
     */
    public function __construct(array $pools)
    {
        foreach($pools as $pool) {
            $this->addPool($pool);
        }
    }

    /**
     * Add a pool to the manager
     *
     * @param PoolInterface $pool
     */
    public function addPool(PoolInterface $pool)
    {
        $this->pools[] = $pool;
    }

    /**
     * Run the manager by looping through the pools and starting the workers.
     *
     * @return void
     */
    public function run()
    {
        if(empty($this->pools)) {
            $this->getOutput()->write('[*] No pools found. Exiting.');
            exit(1);
        } else {
            $this->getOutput()->write("[*] Current PID: " . getmypid());

            foreach($this->pools as $pool) {
                $pool->boot();
            }

            $this->getOutput()->write('[*] Workers started');

            $this->handler = new Handler($this);

            while(true) {
                foreach($this->pools as $pool) {
                    $pool->ping();
                }

                sleep(1);
            }
        }
    }

    /**
     * Set the instance of OutputInterface
     *
     * @param OutputInterface $output
     * @return mixed
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Returns an instance of OutputInterface
     *
     * @return OutputInterface
     */
    public function getOutput()
    {
        if(is_null($this->output)) {
            $this->output = new Console();
        }

        return $this->output;
    }
}