<?php

namespace App\Providers;

use Core\Config;
use Core\Providers\ServiceProviderInterface;

class ConfigServiceProvider implements ServiceProviderInterface {

    public function bindDependencies(): self
    {
        Config::init(__DIR__."/../../../.env.yml");

        return $this;
    }

    public function execute() {
        Config::getInstance();
    }
}
