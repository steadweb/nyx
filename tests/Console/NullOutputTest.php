<?php

namespace Nyx\Tests\Console;

use Nyx\NullOutput;

class NullOutputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NullOutput
     */
    protected $console;

    public function setUp()
    {
        $this->console = new NullOutput();
    }

    public function testOutput()
    {
        $this->assertNull($this->console->write('This should not output anything'));
    }

    public function testBufferThenOutput()
    {
        $this->console->buffer('This will also not output');
        $this->assertNull($this->console->flush(true));
    }

    public function testFalseFlushNullOutput()
    {
        $this->console->buffer('Nothing to see here');
        $this->assertNull($this->console->flush(false));
    }
}