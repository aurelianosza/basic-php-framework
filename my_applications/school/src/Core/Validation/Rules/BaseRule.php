<?php

namespace Core\Validation\Rules;

use Error;
use ReflectionClass;

abstract class BaseRule {

    private static $rules = [];

    public static function addRule(string $ruleName, $rule)
    {
        self::$rules[$ruleName] = $rule;
    }

    public static function makeRule($ruleName): BaseRule
    {
        if ($ruleName instanceof BaseRule) {
            return $ruleName;
        }

        $parts = explode(":", $ruleName, 2);

        $rule = $parts[0];
        $params = $parts[1] ?? null;

        if (!isset(self::$rules[$rule])) {
            throw new Error("Rule $rule not exists");
        }

        return new self::$rules[$rule]($params);
    }

    public function getRuleName(): string
    {
        $className = (new ReflectionClass(static::class))->getShortName();

        return kebab_case($className);
    }

    public abstract function validate(mixed $value): bool;
    public abstract function messageError(): string;

}
