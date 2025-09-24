<?php

use App\Controllers\UseCases\RegisterAnUserInClass;
use App\Entities\CourseClass;
use App\Entities\User;
use App\Entities\UserClass;
use App\Exceptions\ClassDateOutOfPeriodException;
use App\Exceptions\ClassDisabledException;
use App\Exceptions\UserAlreadyRegisteredInClass;
use App\Exceptions\UserAlreadyRegisteredInClassFromThisCourseException;
use Core\Validation\Exception\ValidationException;
use tests\Factories\CourseClassFactory;
use tests\Factories\CourseFactory;
use tests\Factories\UserFactory;
use tests\Utils\FakerMini;

describe("Registering user in class suit test.", function () {

    beforeEach(function () {
        UserClass::repository()
            ->delete([]);
        User::repository()
            ->delete([]);
    });

    it("fails to save user class register with invalid class_id", function (mixed $userId) {
        $selectedClass = container(CourseClassFactory::class)
            ->createWithCourse();

        $userClassData = [
            "user_id" => $userId,
            "class_id" => $selectedClass->id
        ];

        $registerUserClassController = container(RegisterAnUserInClass::class);

        $requestBody = requestMock($userClassData);

        expect(fn () => $registerUserClassController
            ->execute(
                $requestBody,
                responseMock()
            ))->toThrow(ValidationException::class);
    })->with([
        [null],
        ["some-foo-var"],
        [0],
        [FakerMini::number()]
    ]);

    it("fails to save user class register with invalid user_id", function (mixed $classId) {
        $selectedUser = container(UserFactory::class)
            ->create();

        $userClassData = [
            "user_id" => $selectedUser->id,
            "class_id" => $classId
        ];

        $registerUserClassController = container(RegisterAnUserInClass::class);

        $requestBody = requestMock($userClassData);

        expect(fn () => $registerUserClassController
            ->execute(
                $requestBody,
                responseMock()
            ))->toThrow(ValidationException::class);
    })->with([
        [null],
        ["some-foo-var"],
        [0],
        [FakerMini::number()]
    ]);

    it("fails on user already registered in class", function () {
        $selectedClass = container(CourseClassFactory::class)
            ->createWithCourse();

        $selectedUser = container(UserFactory::class)
            ->create();

        $userClassData = [
            "class_id" => $selectedClass->id,
            "user_id" => $selectedUser->id
        ];

        UserClass::repository()
            ->insert($userClassData);

        $registerUserClassController = container(RegisterAnUserInClass::class);

        $requestBody = requestMock($userClassData);

        expect(fn () => $registerUserClassController
            ->execute(
                $requestBody,
                responseMock()
            ))->toThrow(UserAlreadyRegisteredInClass::class);
    });

    it("fails on user already registered in class from same course", function () {
        $course = container(CourseFactory::class)
            ->create();

        $classAlreadyRegistered = container(CourseClassFactory::class)
            ->create([
                "course_id" => $course->id
            ]);

        $newClassFromSameCourse = container(CourseClassFactory::class)
            ->create([
                "course_id" => $course->id
            ]);

        $selectedUser = container(UserFactory::class)
            ->create();

        UserClass::repository()
            ->insert([
                "class_id" => $classAlreadyRegistered->id,
                "user_id" => $selectedUser->id
            ]);
        
        $userClassData = [
            "class_id" => $newClassFromSameCourse->id,
            "user_id" => $selectedUser->id
        ];

        $registerUserClassController = container(RegisterAnUserInClass::class);

        $requestBody = requestMock($userClassData);

        expect(fn () => $registerUserClassController
            ->execute(
                $requestBody,
                responseMock()
            ))->toThrow(UserAlreadyRegisteredInClassFromThisCourseException::class);
    });

    it("fails on save user class with class not available", function () {
        $selectedClass = container(CourseClassFactory::class)
            ->createWithCourse([
                "status" => FakerMini::random([CourseClass::STATUS_DESABILITADO, CourseClass::STATUS_ENCERRADO])
            ]);

        $selectedUser = container(UserFactory::class)
            ->create();

        $userClassData = [
            "class_id" => $selectedClass->id,
            "user_id" => $selectedUser->id,
        ];

        $registerUserClassController = container(RegisterAnUserInClass::class);

        $requestBody = requestMock($userClassData);

        expect(fn () => $registerUserClassController
            ->execute(
                $requestBody,
                responseMock()
            ))->toThrow(ClassDisabledException::class);
    });

    it("fails on save user class with date out of range", function () {
        $selectedClass = container(CourseClassFactory::class)
            ->createWithCourse([
                "status" => CourseClass::STATUS_DISPONIVEL,
                "start_date" => (new DateTime())->modify("-15 days")->format("Y-m-d"),
                "end_date" => (new DateTime())->modify("-7 days")->format("Y-m-d")
            ]);

         $selectedUser = container(UserFactory::class)
            ->create();

        $userClassData = [
            "class_id" => $selectedClass->id,
            "user_id" => $selectedUser->id,
        ];

        $registerUserClassController = container(RegisterAnUserInClass::class);

        $requestBody = requestMock($userClassData);

        expect(fn () => $registerUserClassController
            ->execute(
                $requestBody,
                responseMock()
            ))->toThrow(ClassDateOutOfPeriodException::class);
    });
});
