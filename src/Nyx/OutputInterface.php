<?php

namespace Nyx;

interface OutputInterface
{
    /**
     * Write the message.
     *
     * @param string $msg
     * @return string
     */
    public function write($msg);

    /**
     * Clear the buffer.
     *
     * $write allows the developer to flush the buffer without outputting.
     *
     * @param bool $write
     * @return mixed
     */
    public function flush($write = true);

    /**
     * Buffer a message ready for output
     *
     * @param string $msg
     * @return $this
     */
    public function buffer($msg);
}