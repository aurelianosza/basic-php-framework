<?php

namespace Core\Providers;

interface ServiceProviderInterface {
    public function bindDependencies(): self;
    public function execute();
}
