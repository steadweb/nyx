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

interface PoolInterface
{
    /**
     * Ability to set the worker instance.
     * Immutable - meaning only 1 type of worker can be assigned to the pool.
     *
     * @param  WorkerInterface $worker
     * @throws \Exception
     *
     * * @return void
     */
    public function setWorkerInstance(WorkerInterface $worker);

    /**
     * Adds a worker to the pool.
     *
     * @param  WorkerInterface $worker
     * @return mixed
     */
    public function add(WorkerInterface $worker);

    /**
     * Boot the bool by opening all the workers.
     *
     * @return mixed
     */
    public function boot();

    /**
     * Rebuilds the pool by destroying each child worker and creating new ones.
     *
     * @return bool
     */
    public function rebuild();

    /**
     * Swans a new worker
     *
     * @return mixed
     */
    public function spawn();

    /**
     * Kills a worker within the current pool. Can provide a specific PID.
     *
     * @param  $pid
     * @return mixed
     */
    public function kill($pid = null);

    /**
     * Kills all workers within the pool.
     *
     * @return mixed
     */
    public function killAll();

    /**
     * Ping all workers within the pool and keep them alive.
     *
     * @return mixed
     */
    public function ping();

    /**
     * Return the array of workers.
     *
     * @return array
     */
    public function getWorkers();
}
