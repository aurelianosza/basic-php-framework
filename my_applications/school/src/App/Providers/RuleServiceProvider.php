<?php

namespace App\Providers;

use Core\Config;
use Core\Providers\ServiceProviderInterface;
use Core\Validation\Rules\BaseRule;
use Core\Validation\Rules\{
    Required,
    Min,
    Max,
    MinLength,
    MaxLength,
    Integer,
    RuleDate,
    Email
};

class RuleServiceProvider implements ServiceProviderInterface {

    public function bindDependencies(): self
    {
        BaseRule::addRule("required", Required::class);
        BaseRule::addRule("min", Min::class);
        BaseRule::addRule("max", Max::class);
        BaseRule::addRule("min_length", MinLength::class);
        BaseRule::addRule("max_length", MaxLength::class);
        BaseRule::addRule("integer", Integer::class);
        BaseRule::addRule("date", RuleDate::class);
        BaseRule::addRule("email", Email::class);

        return $this;
    }

    public function execute() {
        
    }
}
