<?php

use App\Controllers\CourseClassController;
use App\Entities\Course;
use App\Entities\CourseClass;
use Core\Validation\Exception\ValidationException;
use tests\Factories\CourseClassFactory;
use tests\Factories\CourseFactory;
use tests\Utils\FakerMini;

describe("Classes suit test.", function () {

    beforeEach(function () {
        CourseClass::repository()
            ->delete([]);
    });

    it("fails to save class with invalid course_id", function (mixed $course_id) {
        $classData = container(CourseClassFactory::class)
            ->make([
                "course_id" => $course_id
            ]);

        
        /** @var  App\Controllers\CourseClassController*/
        $courseClassController = container(CourseClassController::class);

        $requestData = requestMock($classData);

        expect(fn () => $courseClassController
            ->store(
                $requestData,
                jsonResponseMock()
            ))
            ->toThrow(ValidationException::class);

    })->with([
        [null],
        [FakerMini::number()]
        [FakerMini::string()]
        ["some-foo-value"]
    ]);

    it("fails to save class with invalid title", function (mixed $title) {
        $classData = container(CourseClassFactory::class)
            ->make([
                "title" => $title
            ]);

        /** @var App\Controllers\CourseClassController */
        $courseClassController = container(CourseClassController::class);

        $requestData = requestMock($classData);

        expect(fn () => $courseClassController
            ->store(
                $requestData,
                jsonResponseMock()
            ))
            ->toThrow(ValidationException::class);
    })->with([
        [null],
        [FakerMini::number()],
        [FakerMini::string(4)],
        [FakerMini::string(65)]
    ]);

    it("fails to save class with invalid description", function (mixed $description) {
        $classData = container(CourseClassFactory::class)
            ->make([
                "description" => $description
            ]);

        /** @var App\Controllers\CourseClassController */
        $courseClassController = container(CourseClassController::class);

        $requestData = requestMock($classData);

        expect(fn () => $courseClassController
            ->store(
                $requestData,
                jsonResponseMock()
            ))
            ->toThrow(ValidationException::class);
    })->with([
        [null],
        [FakerMini::number()],
        [FakerMini::string(19)],
        [FakerMini::string(513)],
    ]);

    it("fails to save class with invalid vacancies number", function (mixed $vacancies) {
        $classData = container(CourseClassFactory::class)
            ->make([
                "vacancies" => $vacancies
            ]);

        /** @var App\Controllers\CourseClassController */
        $courseClassController = container(CourseClassController::class);

        $requestData = requestMock($classData);

        expect(fn () => $courseClassController
            ->store(
                $requestData,
                jsonResponseMock()
            ))
            ->toThrow(ValidationException::class);
    })->with([
        [null],
        [FakerMini::name()],
        ["some-invalid-value"],
        [0],
        [FakerMini::number(255)]
    ]);

    it("fails to save class with invalid start_date", function (mixed $startDate) {
        $classData = container(CourseClassFactory::class)
            ->make([
                "start_date" => $startDate
            ]);

        /** @var App\Controllers\CourseClassController */
        $courseClassController = container(CourseClassController::class);

        $requestData = requestMock($classData);

        expect(fn () => $courseClassController
            ->store(
                $requestData,
                jsonResponseMock()
            ))
            ->toThrow(ValidationException::class);
    })->with([
        [null],
        [FakerMini::name()],
        ["some-invalid-value"],
        ["18/07/2025"],
        ["1997-16-10"],
        ["1993-12-47"],
        ["2005-02-30"],
        [FakerMini::number(255)]
    ]);

        it("fails to save class with invalid end_date", function (mixed $endDate) {
        $classData = container(CourseClassFactory::class)
            ->make([
                "end_date" => $endDate
            ]);

        /** @var App\Controllers\CourseClassController */
        $courseClassController = container(CourseClassController::class);

        $requestData = requestMock($classData);

        expect(fn () => $courseClassController
            ->store(
                $requestData,
                jsonResponseMock()
            ))
            ->toThrow(ValidationException::class);
    })->with([
        [null],
        [FakerMini::name()],
        ["some-invalid-value"],
        ["18/07/2025"],
        ["1997-16-10"],
        ["1993-12-47"],
        ["2005-02-30"],
        [FakerMini::number(255)]
    ]);

    it("should create a class with success", function () {
        $course = container(CourseFactory::class)
            ->create();

        $classData = container(CourseClassFactory::class)
            ->make([
                "course_id" => $course->id
            ]);

        /** @var  App\Controllers\CourseClassController*/
        $courseClassController = container(CourseClassController::class);

        $requestData = requestMock($classData);

        $responseData = $courseClassController
            ->store(
                $requestData,
                jsonResponseMock()
            );

        expect($responseData->id)
            ->toBeInt();
    });
});
