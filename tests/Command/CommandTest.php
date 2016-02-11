<?php

namespace Nyx\Tests\Command;

use Nyx\Command;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Command
     */
    protected $command;

    public function setUp()
    {
        $this->command = new Command('echo foo', array('bar' => 'baz'));
    }

    public function testGetOptionFromCommand()
    {
        $this->assertEquals('baz', $this->command->getOption('bar'));
        $this->assertEquals('foo', $this->command->getOption('baz', 'foo'));
    }

    public function testOptions()
    {
        $cmd = new Command('echo foo');

        $this->assertInternalType('array', $this->command->getOptions());
        $this->assertInternalType('array', $cmd->getOptions());
    }

    public function testInvalidCommandObject()
    {
        $this->setExpectedException('\Exception');
        $cmd = new Command(new \stdClass());
    }

    public function testInvalidCommandArray()
    {
        $this->setExpectedException('\Exception');
        $cmd = new Command(array());
    }

    public function testInvalidCommandBool()
    {
        $this->setExpectedException('\Exception');
        $cmd = new Command(false);
    }

    public function testInvalidCommandInteger()
    {
        $this->setExpectedException('\Exception');
        $cmd = new Command(12345);
    }
}
