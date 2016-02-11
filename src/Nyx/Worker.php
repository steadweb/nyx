<?php

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
        if($this->started === false) {
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
        if(is_null($this->output)) {
            $this->setOutput(new Console());
        }

        return $this->output;
    }
}