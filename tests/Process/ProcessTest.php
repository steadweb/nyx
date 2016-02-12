<?php

namespace Nyx\Tests\Process;

use Nyx\Command;
use Nyx\NullOutput;
use Nyx\Process;

class ProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Process
     */
    protected $process;

    /**
     * @var Command
     */
    protected $command;

    public function setUp()
    {
        $this->command = new Command('php Fixtures/foo.php');
        $this->process = new Process($this->command);
        $this->process->setOutput(new NullOutput());
    }

    public function testSetCommand()
    {
        $this->assertEquals('php Fixtures/foo.php', $this->process->getCommand()->getCmd());

        $command = new Command('php Fixtures/bar.php');
        $this->process->setCommand($command);

        $this->assertEquals('php Fixtures/bar.php', $this->process->getCommand()->getCmd());
    }

    public function testOpenProcess()
    {
        $process = $this->getMockBuilder('\Nyx\Process')
            ->setConstructorArgs(array($this->command))
            ->setMethods(array('isRunning', 'open'))
            ->getMock();

        $process->expects($this->once())->method('isRunning')->willReturn(true);

        $process->open();
        $this->assertEquals(1, $process->status());

        $process->close();

        $this->process->open();
        $this->assertEquals(1, $this->process->status());
        $this->assertTrue($this->process->isRunning());
        $this->process->close();
    }

    public function testStoppedProcess()
    {
        $process = $this->getMockBuilder('\Nyx\Process')
            ->setConstructorArgs(array($this->command))
            ->setMethods(array('isRunning'))
            ->getMock();

        $process->expects($this->once())->method('isRunning')->willReturn(false);

        $this->assertEquals(2, $process->status());

        $process->close();

        $this->process->open();
        $this->assertEquals(1, $this->process->status());
        $this->assertTrue($this->process->isRunning());
        $this->process->close();

        $this->assertEquals(2, $this->process->status());
        $this->assertFalse($this->process->isRunning());

    }

    public function testGetOutput()
    {
        $this->assertInstanceOf('Nyx\NullOutput', $this->process->getOutput());

        $process = new Process($this->command);
        $this->assertInstanceOf('Nyx\Console', $process->getOutput());
    }
}