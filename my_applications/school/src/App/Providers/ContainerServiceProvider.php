<?php

namespace App\Providers;

use App\Services\PictureService\PictureUploadService;
use Core\Providers\ServiceProviderInterface;
use App\Services\RegistrationService;
use Core\Container;
use Core\Http\Request;

class ContainerServiceProvider implements ServiceProviderInterface {

    private Container $container;

    public function __construct()
    {
        $this->container = Container::getInstance();

    }

    public function bindDependencies(): self
    {
        $this->container
            ->bind('request', Request::mountRequest());

        $this->container
            ->bind("registrationService", new RegistrationService());

        $this->container
            ->bind("coursePictureRepository", new PictureUploadService("courses"));

        return $this;
    }

    public function execute()
    {}
}
