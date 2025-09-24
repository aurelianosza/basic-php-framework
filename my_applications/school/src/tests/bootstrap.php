<?php

include __DIR__ . "/../../autoload.php";
include __DIR__ . "/../Core/helpers.php";
include __DIR__ . "/test_helpers.php";

use App\Entities\CourseClass;
use App\Entities\UserClass;
use App\Providers\RuleServiceProvider;
use Core\Database\BaseRepository;
use tests\PestServiceProvider;
use tests\Utils\PestConfigServiceProvider;

PestServiceProvider::generateInstance()
    ->appendServiceProvider(new RuleServiceProvider())
    ->appendServiceProvider(new PestConfigServiceProvider())
    ->bindDependencies();
