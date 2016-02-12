# Nyx

[![Build Status](https://travis-ci.org/steadweb/nyx.svg?branch=master)](https://travis-ci.org/steadweb/nyx)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/steadweb/nyx/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/steadweb/nyx/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/steadweb/nyx/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/steadweb/nyx/?branch=master)

A PHP process manager of the night.

## Getting started

Download the latest .phar from https://steadweb.github.io/nyxgit

```
wget https://steadweb.github.io/nyx/nyx.phar
```

Or install using composer

```
composer require steadweb/nyx ~0.1
```

## Configuration

Using `nyx.phar` requires you to provide the location of your `nyx.json`. The configuration files suggests where your workers are located, how many workers the manager should spawn and whether to log the outout / errors to file.

Example.

```
{
  "workers": [
    {
      "count": "1",
      "path": "ping 8.8.8.8",
      "options": {
        "in": {
          "type": "pipe",
          "command": "r"
        },
        "out": {
          "type": "file",
          "command": "/tmp/google-ping.log",
          "options": "a"
        },
        "err": {
          "type": "file",
          "command": "/tmp/google-ping-error.log",
          "options": "a"
        }
      }
    },
    {
      "count": "1",
      "path": "ping 8.8.4.4"
    },
    {
      "path": "php foo.php"
    }
  ]
}
```

This sample configuration will create three pools, each with one worker assigned. The first two pools will continue to ping their respective IPs `8.8.8.8` and `8.8.4.4`. We'll come back to the third pool later on.

## Basic usage

Once you've created your `nyx.json` run the following command:

`php nyx.phar run /path/to/nyx.json`

And you should see the following output:

```
[*] Current PID: XXX
[+] Starting worker
[+] New process with command: php /path/to/worker/foo.php
[*] Workers started
[*] Handler registered
```

### Notes

- The current PID is the process ID of the nyx manager. At any given point you can track this using a process monitor, i.e. `top`, if using a linux / unix system.

- Each pool that's suggested within your config will tell you each time it creates a new worker and a new process.

- The handler registered log allows us to catch `SIGTERM` signals.

To exit the manager, simply press `CTRL + C` and all sub processes will exit along with the manager.

## Working example: foo.php

Our example configuration had three pools; two of those pools create one worker in each which would ping IPs. The third pool creates one worker which runs a PHP script, `foo.php`.

```
...
{
    "path": "php foo.php"
}
```

The example `foo.php` script is below. Take a look at the example, you'll notice the script ends after ~5 seconds.

### Code
```
<?php

$count = 5;

while($count > 0) {
    print "Count down..{$count}\n";
    $count = $count - 1;
    sleep(1);
}
```

`Nyx` keeps track of each worker within the assigned pool and restarts the process if the worker has been signaled or has stopped, meaning `Nyx` will spawn an new worker for you.

```
...
[x] Worker stopped. Spawning a new one.
[x] Worker closed.
[+] Starting worker
[+] New process with command: php /Volumes/Dev/Nyx/foo.php
```

As long as a worker process is deemed as `running` `Nyx` will not spawn a new worker until that process has been signaled or has stopped. `Nyx` will only ever spawn the amount of workes you have defined within config.

## Testing

Checkout the repository from Github and run the following commands:

```
composer install
php ./vendor/bin/phpunit -c ./phpunit.xml.dist ./tests
```

## License

`steadweb/nyx` is licensed under MIT.