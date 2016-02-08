<?php

namespace Nyx;

class Pool implements PoolInterface
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
        foreach($this->getWorkers() as $key => $worker) {
            $worker->getProcess()->close();
            unset($this->workers[$key]);
        }

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
        } else {
            throw new \Exception('Maximum number of workers allocated for this pool ('.$this->numberOfWorkers.').');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function kill($pid = null)
    {
        // Kill the first worker
        if(is_null($pid)) {
            $worker = reset($this->workers);
            $worker->getProcess()->close();
            unset($this->workers[key($this->workers)]);
        }

        // @todo support PID find and close
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Spawn the amount of workers required, if not already set.
        while(count($this->workers) < $this->numberOfWorkers) {
            $this->spawn();
        }

        foreach($this->workers as $worker) {
            // Only start the worker if it isn't running
            $worker->start();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWorkers()
    {
        return (array) $this->workers;
    }
}