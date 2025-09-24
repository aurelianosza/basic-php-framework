<?php

namespace Core\Http;

use Core\Container;
use Exception;
use Reflection;
use ReflectionClass;

class Router
{
    private static $routerSingleton = null;

    public static function getInstance(): Router
    {
        if (!self::$routerSingleton) {
            self::$routerSingleton = new Router();
        }

        return self::$routerSingleton;
    }    
    
    private Container $container;

    private function __construct()
    {
        $this->container = Container::getInstance();
    }

    protected array $routes = [];

    public function get(string $pattern, callable|array $handler): void
    {
        $this->addRoute('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable|array $handler): void
    {
        $this->addRoute('POST', $pattern, $handler);
    }

    protected function addRoute(string $method, string $pattern, $handler): void
    {
        $regex = preg_replace('#:([\w]+)#', '(?P<$1>[^/]+)', $pattern);
        $regex = "#^" . $regex . "$#";

        preg_match_all('/:(\w+)/', $pattern, $paramMatches);
        $paramNames = $paramMatches[1];

        $this->routes[$method][] = [
            'regex' => $regex,
            'handler' => $handler,
            'route_params'  => $paramNames,
        ];
    }

    public function dispatch(string $method, string $uri)
    {
        $method = strtoupper($method);
        $uri =  str_replace(config("router.base_path_replace"), "", parse_url($uri, PHP_URL_PATH));

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['regex'], $uri, $matches)) {
                $params = array_filter(
                    $matches,
                    fn($key) => !is_int($key),
                    ARRAY_FILTER_USE_KEY
                );

                return $this->invoke($route, $params);
            }
        }

        http_response_code(404);
        return "404 Not Found";
    }

    protected function invoke($routeDefinition, array $routeParams)
    {
        $handler = $routeDefinition["handler"];
        
        if (
            is_array($handler) &&
            class_exists($handler[0])
        ) {
            $controller = $this->container->resolve($handler[0]);

            $params = $this->getMethodInjections($handler, $routeParams);

            return call_user_func_array([$controller, $handler[1]], $params);
        }


        throw new Exception("Invalid route handler");
    }

    private function getMethodInjections($definitions, $routeParams): array
    {
        [$controller, $method] = $definitions;

        $controllerReflection = new ReflectionClass($controller);

        $methodReflection = $controllerReflection
            ->getMethod($method);

        $params = $methodReflection->getParameters();

        return array_map(
            function ($reflectionParam) use ($routeParams) {

                $type = $reflectionParam->getType();
                $paramName = $reflectionParam->getName();

                $isPrimitive = in_array($type, ["int", "string"]);

                if ($isPrimitive && isset($routeParams[$paramName])) {
                    return $routeParams[$paramName];
                }

                return $this->container->resolve($reflectionParam->getType(), $reflectionParam->getName());
            },
            $params
        );
    }
}
