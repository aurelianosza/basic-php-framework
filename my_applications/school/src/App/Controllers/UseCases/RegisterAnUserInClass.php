<?php

namespace App\Controllers\UseCases;

use App\Entities\CourseClass;
use App\Entities\User;
use App\Entities\UserClass;
use App\Exceptions\ClassDateOutOfPeriodException;
use App\Exceptions\ClassDisabledException;
use App\Exceptions\UserAlreadyRegisteredInClass;
use App\Exceptions\UserAlreadyRegisteredInClassFromThisCourseException;
use Core\Http\Controller;
use Core\Http\Request;
use Core\Http\Response;
use Core\Validation\Rules\Exists;
use DateTime;

class RegisterAnUserInClass extends Controller {

    public function execute(Request $request, Response $jsonResponse)
    {
        $request->validate([
            "user_id" => ["required", new Exists("id", User::repository())],
            "class_id" => ["required", new Exists("id", CourseClass::repository())]
        ]);

        /** @var CourseClass */
        $class = CourseClass::repository()
            ->where("id", $request->input("class_id"))
            ->first();
        
        /** @var User */
        $user = User::repository()
            ->where("id", $request->input("user_id"))
            ->first();
        
        $this->verifyUseCase($class, $user);

        $currentTime = new DateTime("now");
        $userClass = UserClass::repository()
            ->insert([
                "user_id" => $user->id,
                "class_id" => $class->id,
                "registered_at" => $currentTime->format('Y-m-d H:i:s')
            ]);

        return $jsonResponse
            ->withData([
                "registration" => $userClass,
                "user" => $user,
                "class" => $class
            ])
            ->respond();
    }

    private function verifyUseCase(CourseClass $currentClass, User $user): void
    {
        match (true) {
            $this->verifyUserIsAlreadyRegisteredInClass($currentClass, $user) => throw new UserAlreadyRegisteredInClass(),
            $this->verifyUserIsRegisteredInClassFromSameCourse($currentClass, $user) => throw new UserAlreadyRegisteredInClassFromThisCourseException(),
            !$currentClass->isAvailable() => throw new ClassDisabledException(),
            !$currentClass->isInValidPeriod() => throw new ClassDateOutOfPeriodException(),
            default => null
        };
    }

    private function verifyUserIsAlreadyRegisteredInClass(CourseClass $currentClass, User $user): bool
    {
        /** @var array */
        $userClasses = UserClass::repository()
            ->where("class_id", $currentClass->id)
            ->where("user_id", $user->id)
            ->fetch();

        return count($userClasses) > 0;
    }

    private function verifyUserIsRegisteredInClassFromSameCourse(CourseClass $currentClass, User $user): bool
    {
        /** @var array */
        $usersClassesSameCourse = UserClass::repository()
            ->join("classes", "classes.id = user_class.class_id")
            ->join("courses", "classes.course_id = courses.id")
            ->where("user_id", $user->id)
            ->fetch();

        return count($usersClassesSameCourse) > 0;
    }
}
