<?php

namespace Nyx;

interface PoolInterface
{
    /**
     * Ability to set the worker instance.
     * Immutable - meaning only 1 type of worker can be assigned to the pool.
     *
     * @param WorkerInterface $worker
     * @throws \Exception
     *
     * * @return void
     */
    public function setWorkerInstance(WorkerInterface $worker);

    /**
     * Adds a worker to the pool.
     *
     * @param WorkerInterface $worker
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
     * @return int
     */
    public function spawn();

    /**
     * Kills a worker within the current pool. Can provide a specific PID.
     *
     * @param $pid
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