<?php

namespace Nyx;

interface ProcessInterface
{
    /**
     * Set the command
     *
     * @param CommandInterface $command
     * @return void
     */
    public function setCommand(CommandInterface $command);

    /**
     * Return the command
     *
     * @return CommandInterface
     */
    public function getCommand();

    /**
     * Starts the process.
     *
     * @return mixed
     */
    public function open();

    /**
     * Close the process
     *
     * @return void
     */
    public function close();

    /**
     * Determine whether the process is running
     *
     * @return bool
     */
    public function isRunning();

    /**
     * Return an array of errors
     *
     * @return array
     */
    public function errors();

    /**
     * @return mixed
     */
    public function status();
}