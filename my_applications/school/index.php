<?php

require __DIR__ . '/src/Core/helpers.php';
require __DIR__ . '/autoload.php';

use App\Providers\ConfigServiceProvider;
use App\Providers\ContainerServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\RuleServiceProvider;
use Core\Providers\AppProvider;

ini_set('display_errors', '0');
error_reporting(E_ALL);
register_shutdown_function('pretty_fatal_error');

AppProvider::generateInstance()
    ->appendServiceProvider(new ContainerServiceProvider())
    ->appendServiceProvider(new RouteServiceProvider())
    ->appendServiceProvider(new ConfigServiceProvider())
    ->appendServiceProvider(new RuleServiceProvider())
    ->bindDependencies()
    ->execute();

