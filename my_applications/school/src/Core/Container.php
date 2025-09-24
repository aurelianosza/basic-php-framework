<?php

namespace Core;

use Exception;
use Error;
use ReflectionClass;

class Container
{
    private static $containerSingleton = null;

    public static function getInstance(): Container
    {
        if (!self::$containerSingleton) {
            self::$containerSingleton = new Container();
        }

        return self::$containerSingleton;
    }

    private function __construct()
    {}

    protected array $bindings = [];

    public function bind(string $abstract, $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function resolve(string $className, ?string $paramName = null)
    {
        if ($paramName && isset($this->bindings[$paramName])) {
            return $this->bindings[$paramName];
        }

        if (!class_exists($className)) {
            throw new Error("cant resolve class $className");
        }

        $reflector = new ReflectionClass($className);

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $className;
        }        

        $params = $constructor->getParameters();
        $dependencies = [];

        foreach ($params as $param) {
            $type = $param->getType();

            if ($type && !$type->isBuiltin()) {
                $dependencies[] = $this->resolve($type->getName(), $param->getName());
            } elseif ($param->isDefaultValueAvailable()) {
                $dependencies[] = $param->getDefaultValue();
            } else {
                throw new Exception("Cannot resolve dependency \${$param->getName()} in class {$className}");
            }
        }

        return $reflector->newInstanceArgs($dependencies);
    }
}
