<?php

namespace tests\Utils;

use Core\Config;
use Core\Providers\ServiceProviderInterface;

class PestConfigServiceProvider implements ServiceProviderInterface {

    public function bindDependencies(): self
    {
        Config::init(__DIR__."/../../../.env.test.yml");

        return $this;
    }

    public function execute() {
        Config::getInstance();
    }
}
