<?php

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

        pcntl_signal(SIGTERM, array($this, "catchSignal"));
        pcntl_signal(SIGHUP,  array($this, "catchSignal"));
        pcntl_signal(SIGUSR1, array($this, "catchSignal"));

        $this->manager->getOutput()->write('[*] Handler registered');
    }

    /**
     * @param $signal
     */
    public function catchSignal($signal)
    {
        switch($signal) {
            case SIGTERM:
                $this->manager->getOutput()->write('[-] Exiting.');
                exit(1);
        }
    }
}