<?php

use App\Controllers\CourseController;
use App\Entities\Course;
use Core\Validation\Exception\ValidationException;
use tests\Factories\CourseFactory;
use tests\Utils\FakerMini;

describe("Course crud test suit", function() {

    beforeEach(function () {
        Course::repository()
            ->delete([]);
    });

    it("fails to store course with invalid title", function (?string $title) {
        $courseDetails = container(CourseFactory::class)
            ->make([
                "title" => $title
            ]);

         /** @var App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $requestData = requestMock($courseDetails);

        expect(fn () => $courseController
            ->store(
                $requestData,
                jsonResponseMock()
            ))
            ->toThrow(ValidationException::class);

    })->with([
        [null],
        [FakerMini::string(4)],
        [FakerMini::string(129)],
    ]);

    it("fails to store course with invalid description", function (?string $description) {
        $courseDetails = container(CourseFactory::class)
            ->make([
                "description" => $description
            ]);

         /** @var  App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $requestData = requestMock($courseDetails);

        expect(fn () => $courseController
            ->store(
                $requestData,
                jsonResponseMock()
            ))
            ->toThrow(ValidationException::class);

    })->with([
        [null],
        [FakerMini::string(19)],
        [FakerMini::string(523)],
    ]);

    it("fails to store course with invalid theme", function (mixed $theme) {
        $courseDetails = container(CourseFactory::class)
            ->make([
                "theme" => $theme
            ]);

         /** @var App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $requestData = requestMock($courseDetails);

        expect(fn () => $courseController
            ->store(
                $requestData,
                jsonResponseMock()
            ))
            ->toThrow(ValidationException::class);

    })->with([
        [null],
        [FakerMini::string(19)],
        [FakerMini::number()],
        [FakerMini::string()],
        ["some-foo-value"]
    ]);

    it("should insert a course in database with data", function() {
        $courseDetails = container(CourseFactory::class)
            ->make();

         /** @var  App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $requestData = requestMock($courseDetails);

        $courseController
            ->store(
                $requestData,
                jsonResponseMock()
            );

        $selectedCourse = Course::repository()
            ->where("title", $courseDetails["title"])
            ->where("description", $courseDetails["description"])
            ->where("theme", $courseDetails["theme"])
            ->where("url_image", $courseDetails["url_image"])
            ->first();

        expect($selectedCourse)
            ->not
            ->toBeNull();
    });

    it("should return 404 on show a not existing course", function () {
        /** @var App/Entities/Course */
        $courseRegister = container(CourseFactory::class)
            ->create();

        /** @var  App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $responseBody = $courseController
            ->show(
                $courseRegister->id + 1,
                jsonResponseMock()
            );
            
        expect($responseBody)
            ->toBe([
                "message" => "Model not found"
            ]);
    });

    it("should show a course with success", function () {
        /** @var App/Entities/Course */
        $courseRegister = container(CourseFactory::class)
            ->create();

         /** @var  App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $responseBody = $courseController
            ->show(
                $courseRegister->id,
                jsonResponseMock()
            );

        expect($courseRegister->title)
            ->toBe($responseBody->title);
        expect($courseRegister->description)
            ->toBe($responseBody->description);
        expect($courseRegister->theme)
            ->toBe($responseBody->theme);
        expect($courseRegister->url_image)
            ->toBe($responseBody->url_image);
    });

    it("should validate update course on title data is invalid", function (?string $title) {
        $courseDetails = container(CourseFactory::class)
            ->make([
                "title" => $title
            ]);

         /** @var  App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $requestData = requestMock($courseDetails);

        expect(fn () => $courseController
            ->store(
                $requestData,
                jsonResponseMock()
            ))
            ->toThrow(ValidationException::class);

    })->with([
        [null],
        [FakerMini::string(4)],
        [FakerMini::string(129)],
    ]);

    it("should update a course with given data with success", function () {
        $courseDetails = container(CourseFactory::class)
            ->make();

        /** @var App/Entities/Course */
        $courseRegister = Course::repository()
            ->insert($courseDetails);

        $dataToUpdate = container(CourseFactory::class)
            ->make();

         /** @var  App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $requestBody = requestMock($dataToUpdate);

        $courseController
            ->update(
                $requestBody,
                $courseRegister->id,
                jsonResponseMock()
            );

        /** @var App/Entities/Course  */
        $updatedCourseRegister = Course::repository()
            ->where("id", $courseRegister->id)
            ->first();

        expect($updatedCourseRegister->title)
            ->toBe($dataToUpdate["title"]);
        expect($updatedCourseRegister->description)
            ->toBe($dataToUpdate["description"]);
        expect($updatedCourseRegister->theme)
            ->toBe($dataToUpdate["theme"]);
        expect($updatedCourseRegister->url_image)
            ->toBe($dataToUpdate["url_image"]);
    });

    it("should delete a course with success", function () {
        $courseRegister = container(CourseFactory::class)
            ->create();

         /** @var  App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $courseController
            ->destroy(
                $courseRegister->id,
                jsonResponseMock()
            );

        $deletedCourse = Course::repository()
            ->where("id", $courseRegister->id)
            ->first();

        expect($deletedCourse)
            ->toBeNull();
    });
});
