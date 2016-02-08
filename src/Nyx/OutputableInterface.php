<?php

namespace Nyx;

interface OutputableInterface
{
    /**
     * Set the instance of OutputInterface
     *
     * @param OutputInterface $output
     * @return mixed
     */
    public function setOutput(OutputInterface $output);

    /**
     * Returns an instance of OutputInterface
     *
     * @return OutputInterface
     */
    public function getOutput();
}