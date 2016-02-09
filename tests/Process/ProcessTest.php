<?php

namespace Nyx\Tests\Process;

use Nyx\Command;
use Nyx\Process;
use Nyx\Tests\Pool\NullOutput;

class ProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Process
     */
    protected $process;

    public function setUp()
    {
        $command = new Command('echo foo');
        $this->process = new Process($command);
        $this->process->setOutput(new NullOutput());
    }

    public function testSetCommand()
    {
        $this->assertEquals('echo foo', $this->process->getCommand()->getCmd());

        $command = new Command('echo bar');
        $this->process->setCommand($command);

        $this->assertEquals('echo bar', $this->process->getCommand()->getCmd());
    }

    public function testOpenProcess()
    {
        $this->process->open();

        $this->assertTrue(true, $this->process->isRunning());
        $this->assertEquals(1, $this->process->status());

        $this->process->close();
    }

    public function testStoppedProcess()
    {
        $this->process->open();

        $this->assertTrue(true, $this->process->isRunning());
        $this->assertEquals(1, $this->process->status());

        $this->process->close();

        $this->assertFalse(false, $this->process->isRunning());
        $this->assertEquals(2, $this->process->status());

    }
}