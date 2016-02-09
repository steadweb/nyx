#!/usr/bin/env php
<?php

declare(ticks = 1);

require __DIR__.'/vendor/autoload.php';

use Nyx\Commands\NyxCommand;
use Nyx\Commands\TestCommand;
use Symfony\Component\Console\Application;

$application = new Application(\Nyx\Manager::NAME, \Nyx\Manager::VERSION);
$application->add(new NyxCommand());
$application->add(new TestCommand());
$application->run();