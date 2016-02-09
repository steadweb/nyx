<?php

namespace Nyx\Commands;

use Nyx\Manager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NyxCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('nyx:run')
            ->setDescription('Nyx run command which boots your workers.')
            ->addArgument(
                'config',
                InputArgument::REQUIRED,
                'Path to your nyx.json config'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = Manager::factory($input->getArgument('config'));
        $manager->run();
    }
}