<?php

namespace Nyx\Tests\Console;

use Nyx\Console;

class ConsoleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Console
     */
    protected $console;

    public function setUp()
    {
        $this->console = new Console();
    }

    public function testBuffer()
    {
        $this->console->buffer('This is an example');
    }

    public function testFlush()
    {
        $this->console->flush(false);
    }

    public function testFlushAndWrite()
    {
        $this->console->buffer('Again...');

        ob_start();
        $this->console->flush();
        $this->assertEquals("Again...\n", ob_get_contents());
        ob_end_clean();
    }

    public function testWrite()
    {
        ob_start();
        $this->console->write('This is another example.');
        $this->assertEquals("This is another example.\n", ob_get_contents());
        ob_end_clean();
    }
}