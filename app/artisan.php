<?php

require __DIR__.'/vendor/autoload.php';

require __DIR__.'/bootstrap/init.php';

$kernel = new \Oooiik\Test20240706\Console\Karnel();

$kernel->call($argv[1] ?? null);