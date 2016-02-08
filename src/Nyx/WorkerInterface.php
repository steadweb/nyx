<?php

namespace Nyx;

interface WorkerInterface
{
    /**
     * Start the worker
     *
     * @return void
     */
    public function start();

    /**
     * Get the process from the worker
     *
     * @return ProcessInterface
     */
    public function getProcess();
}