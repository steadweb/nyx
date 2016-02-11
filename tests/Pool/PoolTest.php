<?php

namespace Nyx\Tests\Pool;

use Nyx\Command;
use Nyx\OutputInterface;
use Nyx\Pool;
use Nyx\Process;
use Nyx\Worker;

class PoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Pool
     */
    protected $pool;

    protected function setUp()
    {
        $this->pool = new Pool();
        $this->pool->setOutput(new NullOutput());
    }

    public function testPoolNotStartedNumberOfWorkers()
    {
        $this->assertEquals(0, count($this->pool->getWorkers()));
    }

    public function testPoolStartedAndNumberOfWorkers()
    {
        $this->pool->add(new Worker(new Process(new Command('echo foo'))));
        $this->assertEquals(1, count($this->pool->getWorkers()));
    }

    public function testPoolStartedWithMultipleWorkers()
    {
        $pool = new Pool(10);
        $pool->setOutput(new NullOutput());

        $command = new Command('echo foo');
        $process = new Process($command);
        $worker = new Worker($process);

        // Nullify the output
        $process->setOutput(new NullOutput());
        $worker->setOutput(new NullOutput());

        $pool->add($worker);
        $pool->boot();
        $this->assertEquals(10, count($pool->getWorkers()));
    }

    public function testPoolSpawnWorker()
    {
        $pool = new Pool(5);
        $pool->setOutput(new NullOutput());

        $command = new Command('echo foo');
        $process = new Process($command);
        $worker = new Worker($process);

        $process->setOutput(new NullOutput());
        $worker->setOutput(new NullOutput());

        $pool->setWorkerInstance($worker);

        $this->assertEquals(0, count($pool->getWorkers()));

        for($i = 0; $i < 5; $i++) {
            $pool->spawn();
            $this->assertEquals(($i+1), count($pool->getWorkers()));
        }
    }

    public function testPoolKillWorker()
    {
        $pool = new Pool(3);
        $pool->setOutput(new NullOutput());

        $command = new Command('echo foo');
        $process = new Process($command);
        $worker = new Worker($process);

        // Nullify the output
        $process->setOutput(new NullOutput());
        $worker->setOutput(new NullOutput());

        $pool->setWorkerInstance($worker);

        $this->assertEquals(0, count($pool->getWorkers()));

        for($i = 0; $i < 3; $i++) {
            $pool->spawn();
            $this->assertEquals(($i+1), count($pool->getWorkers()));
        }

        $pool->kill();
        $this->assertEquals(2, count($pool->getWorkers()));

        $pool->kill();
        $this->assertEquals(1, count($pool->getWorkers()));

        $pool->boot();
        $this->assertEquals(3, count($pool->getWorkers()));
    }

    public function testPoolRebuild()
    {
        $pool = new Pool(2);
        $pool->setOutput(new NullOutput());

        $command = new Command('echo foo');
        $process = new Process($command);
        $worker = new Worker($process);

        // Nullify the output
        $process->setOutput(new NullOutput());
        $worker->setOutput(new NullOutput());

        $pool->setWorkerInstance($worker);

        $this->assertEquals(0, count($pool->getWorkers()));

        $pool->boot();

        $this->assertEquals(2, count($pool->getWorkers()));

        $pool->rebuild();

        $this->assertEquals(2, count($pool->getWorkers()));
    }

    public function testPoolImmutableWorker()
    {
        $pool = new Pool();
        $pool->setOutput(new NullOutput());

        $command1 = new Command('echo foo');
        $process1 = new Process($command1);
        $worker1 = new Worker($process1);

        // Nullify the output
        $process1->setOutput(new NullOutput());
        $worker1->setOutput(new NullOutput());

        $command2 = new Command('echo bar');
        $process2 = new Process($command2);
        $worker2 = new Worker($process2);

        // Nullify the output
        $process2->setOutput(new NullOutput());
        $worker2->setOutput(new NullOutput());

        $this->setExpectedException('\Exception');

        // Set / Add worker1
        $pool->add($worker1);

        // Invalid worker instance
        $pool->setWorkerInstance($worker2);
        $pool->add($worker2);
    }

    public function testPoolImmutableWorkerAdd()
    {
        $pool = new Pool(2);
        $pool->setOutput(new NullOutput());

        $command1 = new Command('echo foo');
        $process1 = new Process($command1);
        $worker1 = new Worker($process1);

        // Nullify the output
        $process1->setOutput(new NullOutput());
        $worker1->setOutput(new NullOutput());

        $command2 = new Command('echo bar');
        $process2 = new Process($command2);
        $worker2 = new Worker($process2);

        // Nullify the output
        $process2->setOutput(new NullOutput());
        $worker2->setOutput(new NullOutput());

        $this->setExpectedException('\Exception');

        // Set / Add worker1
        $pool->add($worker1);

        // Invalid worker instance
        $pool->add($worker2);
    }

    public function testPoolSpawnTooManyWorkers()
    {
        $pool = new Pool(5);
        $pool->setOutput(new NullOutput());

        $command = new Command('echo foo');
        $process = new Process($command);
        $worker = new Worker($process);

        // Nullify the output
        $process->setOutput(new NullOutput());
        $worker->setOutput(new NullOutput());

        $pool->setWorkerInstance($worker);

        $this->assertEquals(0, count($pool->getWorkers()));

        $this->setExpectedException('\Exception');

        for($i = 0; $i < 6; $i++) {
            $pool->spawn();
            $this->assertEquals(($i+1), count($pool->getWorkers()));
        }
    }
}

class NullOutput implements OutputInterface
{
    public function write($msg){}
    public function flush($write = true){}
    public function buffer($msg){}
}