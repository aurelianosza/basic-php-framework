<?php

namespace tests;

use Core\Providers\ServiceProviderInterface;

class PestServiceProvider implements ServiceProviderInterface {

    public static function generateInstance(): PestServiceProvider
    {
        return new PestServiceProvider();    
    }

    private array $providers = [];

    public function appendServiceProvider(ServiceProviderInterface $serviceProvider): self
    {
        $this->providers[] = $serviceProvider;
        return $this;
    }

    public function bindDependencies(): self
    {
        foreach ($this->providers as $provider) {
            $provider->bindDependencies();
        }

        return $this;
    }

    public function execute() {}
}
