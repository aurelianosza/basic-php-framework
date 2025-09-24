<?php

namespace Core\Providers;

use Core\Container;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Router;
use Exception;
use Core\RenderableException;
use App\Services\RegistrationService;

class AppProvider implements ServiceProviderInterface {

    private Router $router;

    private array $providers = [];

    public static function generateInstance(): AppProvider
    {
        return new AppProvider();    
    }

    private function __construct()
    {
        $this->router = Router::getInstance();
    }

    public function appendServiceProvider(ServiceProviderInterface $serviceProvider): AppProvider
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

    public function execute()
    {
        try {

            echo json_encode($this->router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']));

        } catch (Exception $exception) {

            if ($exception instanceof RenderableException) {
                echo json_encode($exception->render());
                return;
            }

            throw $exception;
        }
    }
}
