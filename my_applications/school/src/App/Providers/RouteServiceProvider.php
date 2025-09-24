<?php

namespace App\Providers;

use Core\Config;
use Core\Http\Router;
use Core\Providers\ServiceProviderInterface;
use App\Controllers\CourseClassController;
use App\Controllers\CourseController;
use App\Controllers\UseCases\RegisterAnUserInClass;
use App\Controllers\UseCases\ShowUsersCourses;
use App\Controllers\UserController;


class RouteServiceProvider implements ServiceProviderInterface {

    private Router $router;

    public function __construct()
    {
        $this->router = Router::getInstance();
    }

    public function bindDependencies(): self
    {
        $this->router->post("/users", [UserController::class, "store"]);
        $this->router->post("/users/:userId/delete", [UserController::class, "destroy"]);

        $this->router->get("/courses", [CourseController::class, "index"]);
        $this->router->post("/courses", [CourseController::class, "store"]);
        $this->router->post("/courses/:courseId/update-picture", [CourseController::class, "changePicture"]);
        $this->router->get("/courses/:courseId", [CourseController::class, "show"]);
        $this->router->post("/courses/:courseId/update", [CourseController::class, "update"]);
        $this->router->post("/courses/:courseId/delete", [CourseController::class, "destroy"]);

        $this->router->get("/course-classes", [CourseClassController::class, "index"]);
        $this->router->post("/course-classes", [CourseClassController::class, "store"]);
        $this->router->get("/course-classes/:courseClassId", [CourseClassController::class, "show"]);
        $this->router->post("/course-classes/:courseClassId/update", [CourseClassController::class, "update"]);

        $this->router->post("/use-cases/register-user-in-class", [RegisterAnUserInClass::class, "execute"]);
        $this->router->get("/use-cases/show-user-courses/:userId", [ShowUsersCourses::class, "execute"]);

        return $this;
    }

    public function execute()
    {}
}
