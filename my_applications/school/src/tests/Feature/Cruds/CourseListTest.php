<?php

use App\Controllers\CourseController;
use App\Entities\Course;
use tests\Factories\CourseFactory;
use tests\Utils\FakerMini;

describe("Course List test suit", function() {

    beforeEach(function() {
        Course::repository()
            ->delete([]);
    });

    it("should return empty array on has no courses registered", function() {
        /** @var  App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $responseBody = $courseController
            ->index(
                requestMock(),
                jsonResponseMock()
            );

        expect($responseBody)
            ->toBeArray();

        expect($responseBody)
            ->toBe([]);
    });

    it("should return item with their details on has course registered", function() {
        $courseDetails = container(CourseFactory::class)
            ->create();

        /** @var  App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $responseBody = $courseController
            ->index(
                requestMock(),
                jsonResponseMock()
            );

        expect($responseBody)
            ->toBeArray();

        $insertedCourse = $responseBody[0];

        expect($insertedCourse->id)
            ->toBeInt();
        expect($insertedCourse->title)
            ->toBe($courseDetails->title);
        expect($insertedCourse->description)
            ->toBe($courseDetails->description);
        expect($insertedCourse->theme)
            ->toBe($courseDetails->theme);
        expect($insertedCourse->url_image)
            ->toBe($courseDetails->url_image);
    });

    it("should return arrange of items registered", function() {
        $coursesCount = FakerMini::number(2, 10);

        $courseFactory = container(CourseFactory::class);

        foreach (range(1, $coursesCount) as $_number) {
            $courseFactory->create();
        }

        /** @var  App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $responseBody = $courseController
            ->index(
                requestMock(),
                jsonResponseMock()
            );

        expect($responseBody)
            ->toBeArray();
        expect(count($responseBody))
            ->toBe($coursesCount);
    });

    it("should filter courses with given title", function () {
        $courseFactory = container(CourseFactory::class);

        $filteredCourseData = $courseFactory
            ->create();

        $coursesCount = FakerMini::number(2, 10);

        foreach (range(1, $coursesCount) as $number) {
            $courseFactory->create();
        }

         /** @var  App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $requestDataWithFilter = requestMock([
            "title" => $filteredCourseData->title
        ]);

        $responseBody = $courseController
            ->index(
                $requestDataWithFilter,
                jsonResponseMock()
            );

        expect($responseBody)
            ->toBeArray();

        expect((array)$responseBody[0])
            ->toContain(
                $filteredCourseData->title,
                $filteredCourseData->description,
                $filteredCourseData->theme,
                $filteredCourseData->url_image,
            );
    });

    it("should filter courses with given theme", function() {
        $courseFactory = container(CourseFactory::class);

        $filteredCourseData = $courseFactory
            ->create();

        $coursesCount = FakerMini::number(2, 10);

        foreach (range(1, $coursesCount) as $number) {
            $selectedTheme = FakerMini::random(Course::THEMES);

            if ($selectedTheme === $filteredCourseData->theme) {
                continue;
            }

            $courseFactory->create([
                "theme" => $selectedTheme
            ]);
        }

         /** @var  App\Controllers\CourseController*/
        $courseController = container(CourseController::class);

        $requestDataWithFilter = requestMock([
            "theme" => $filteredCourseData->theme
        ]);

        $responseBody = $courseController
            ->index(
                $requestDataWithFilter,
                jsonResponseMock()
            );

        expect($responseBody)
            ->toBeArray();

        expect((array)$responseBody[0])
            ->toContain(
                $filteredCourseData->title,
                $filteredCourseData->description,
                $filteredCourseData->theme,
                $filteredCourseData->url_image,
            );
    });
});
