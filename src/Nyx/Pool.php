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

class Pool implements PoolInterface, OutputableInterface
{
    /**
     * Default amount of workers to spawn
     *
     * @var int
     */
    const DEFAULT_WORKER_COUNT = 1;

    /**
     * Immutable. Number of workers this pool maintain
     *
     * @var int
     */
    protected $numberOfWorkers;

    /**
     * Pool of worker objects
     *
     * @var array
     */
    protected $workers = array();

    /**
     * @var WorkerInterface
     */
    protected $workerInstance;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Timestamp of when the pool first started
     *
     * @var
     */
    protected $started;

    /**
     * Pool constructor.
     *
     * @param null $numberOfWorkers
     */
    public function __construct($numberOfWorkers = null)
    {
        if(is_null($numberOfWorkers)) {
            $numberOfWorkers = static::DEFAULT_WORKER_COUNT;
        }

        $this->numberOfWorkers = $numberOfWorkers;
    }

    /**
     * {@inheritdoc}
     */
    public function add(WorkerInterface $worker)
    {
        try {
            $this->setWorkerInstance($worker);
        } catch(\Exception $e) {}

        if($worker->getProcess()->getCommand()->getCmd() === $this->workerInstance->getProcess()->getCommand()->getCmd()) {
            $this->workers[] = $worker;
        } else {
            throw new \Exception('$worker must be an instance of WorkerInterface and the same as ' . get_class($worker));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setWorkerInstance(WorkerInterface $worker)
    {
        if(is_null($this->workerInstance)) {
            $this->workerInstance = clone $worker;
        } else {
            throw new \Exception('setWorkerInstance already called');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rebuild()
    {
        // Close
        $this->killAll();

        // Then spawn new ones
        for($i = 0; $i < $this->numberOfWorkers; $i++) {
            $this->spawn();
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function spawn()
    {
        // Create a clone worker
        if($this->numberOfWorkers > count($this->getWorkers())) {
            $worker = clone $this->workerInstance;
            $this->add($worker);
            $worker->start();
        } else {
            throw new \Exception('Maximum number of workers allocated for this pool ('.$this->numberOfWorkers.').');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function kill($pid = null)
    {
        $worker = false;

        if(is_null($pid)) {
            $worker = reset($this->workers);
            $pid = key($this->workers);
        }
        elseif(array_key_exists($pid, $this->workers)) {
            $worker = $this->workers[$pid];
        }

        if($worker) {
            $worker->getProcess()->close();
            unset($this->workers[$pid]);
            $this->getOutput()->write('[x] Worker closed.');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function killAll()
    {
        foreach($this->workers as $pid => $worker) {
            $this->kill($pid);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if(!$this->started) {
            // Spawn the amount of workers required, if not already set.
            while(count($this->workers) < $this->numberOfWorkers) {
                $this->spawn();
            }

            foreach($this->workers as $worker) {
                // Only start the worker if it isn't running
                $worker->start();
            }

            $this->started = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function ping()
    {
        foreach($this->getWorkers() as $pid => $worker) {
            // If the worker has stopped, restart it
            if(!$worker->getProcess()->isRunning()) {
                $this->getOutput()->write('[x] Worker stopped. Spawning a new one.');
                $this->kill($pid)->spawn();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWorkers()
    {
        return (array) $this->workers;
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
        if(is_null($this->output)) {
            $this->setOutput(new Console());
        }

        return $this->output;
    }
}