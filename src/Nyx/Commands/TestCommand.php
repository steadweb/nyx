<?php

namespace Nyx\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('nyx:tests')
            ->setDescription('Run units test for Nyx.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        exec('php ./vendor/bin/phpunit -c ./tests/phpunit.xml.dist', $testsOutput, $return);

        array_walk($testsOutput, function(&$item) {
            $item .= "\n";
        });

        $output->write($testsOutput);
    }
}