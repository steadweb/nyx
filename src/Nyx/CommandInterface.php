<?php

namespace Nyx;

interface CommandInterface
{
    /**
     * Produce a string version of the command
     *
     * @return string
     */
    public function toString();

    /**
     * Return the full cmd
     *
     * @return mixed
     */
    public function getCmd();

    /**
     * Return the array of options passed to the command
     *
     * @return array
     */
    public function getOptions();

    /**
     * Attempt to return the option passed.
     *
     * @param $option
     * @param $default
     * @return mixed
     */
    public function getOption($option, $default = null);
}