<?php

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
        $this->getOutput()->write("[*] Current PID: " . getmypid());

        foreach($this->pools as $pool) {
            $pool->boot();
        }

        $this->getOutput()->write('[*] Workers started');

        while(true) {
            sleep(15);
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