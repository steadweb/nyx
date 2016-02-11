<?php

namespace Nyx;

class Console implements OutputInterface
{
    /**
     * Array of messages
     *
     * @var array
     */
    protected $buffer = array();

    /**
     * Write the message.
     *
     * @param string $msg
     * @return string
     */
    public function write($msg)
    {
        print($msg) . PHP_EOL;
    }

    /**
     * Clear the buffer.
     *
     * $write allows the developer to flush the buffer without outputting.
     *
     * @param bool $write
     * @return mixed
     */
    public function flush($write = true)
    {
        if(!$write) {
            $this->buffer = array();
        }

        foreach((array)$this->buffer as $msg) {
            $this->write($msg);
        }
    }

    /**
     * Buffer a message ready for output
     *
     * @param string $msg
     * @return $this
     */
    public function buffer($msg)
    {
        if(is_string($msg)) {
            $this->buffer[] = $msg;
        }
    }
}
