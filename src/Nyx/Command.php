<?php

namespace Nyx;

class Command implements CommandInterface
{
    /**
     * Command string
     *
     * @var
     */
    protected $cmd;

    /**
     * Array of options
     *
     * @var array
     */
    protected $options = array();

    /**
     * Command constructor.
     *
     * @param $cmd
     * @param array|null $options
     * @throws \Exception
     */
    public function __construct($cmd, array $options = null)
    {
        if(!is_string($cmd)) {
            throw new \Exception('$cmd must be a string');
        }

        $this->cmd = $cmd;
        $this->options = (array) $options;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return (string) $this->cmd;
    }

    /**
     * {@inheritdoc}
     */
    public function getCmd()
    {
        return $this->cmd;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($option, $default = null)
    {
        if(array_key_exists($option, $this->options)) {
            return $this->options[$option];
        }

        return $default;
    }
}