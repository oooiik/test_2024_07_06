<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;


$env = parse_ini_file('.env');

$capsule = new Capsule();

$capsule->addConnection([
    'driver' => $env["DB_CONNECTION"],
    'host' => $env["DB_HOST"],
    'database' => $env["DB_DATABASE"],
    'username' => $env["DB_USER"],
    'password' => $env["DB_PASSWORD"],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);


// Set the event dispatcher used by Eloquent models... (optional)

$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();