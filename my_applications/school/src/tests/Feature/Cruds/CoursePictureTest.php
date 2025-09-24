<?php

use App\Controllers\CourseController;
use App\Entities\Course;
use App\Services\PictureService\PictureUploadService;
use tests\Factories\CourseFactory;
use tests\Mocks\PictureServiceMock;
use tests\Utils\FakerMini;

describe("Course picture suit test", function () {

    beforeEach(function () {
        Course::repository()
            ->delete([]);
    });

    it("fails on change-image with not existing  course", function () {
        $courseRegister = container(CourseFactory::class)
            ->create();

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

    it("should save a course with returned value from picture service", function () {
        $courseRegister = container(CourseFactory::class)
            ->create();

        $courseController = container(CourseController::class);

        $requestBody = [
            "picture" => FakerMini::string(128)
        ];

        $responseBody = $courseController
            ->changePicture(
                $courseRegister->id,
                requestMock($requestBody),
                new PictureServiceMock(),
                jsonResponseMock()
            );

        /** @var App\Entities\Course  */
        $courseRegisterUpdated = Course::repository()
            ->where("id", $courseRegister->id)
            ->first();

        expect($courseRegisterUpdated->url_image)
            ->toBe("/mocking/" . $requestBody["picture"]);
    });

    it("should call only save on course hasnt url_image", function () {
        $courseRegister = container(CourseFactory::class)
            ->create([
                "url_image" => null
            ]);

        $courseController = container(CourseController::class);

        $requestBody = [
            "picture" => FakerMini::string(128)
        ];

        $pictureServiceMock = Mockery::mock(PictureUploadService::class);

        $pictureServiceMock->shouldReceive("save")
            ->with($requestBody["picture"])
            ->andReturn($requestBody["picture"]);

        $responseBody = $courseController
            ->changePicture(
                $courseRegister->id,
                requestMock($requestBody),
                $pictureServiceMock,
                jsonResponseMock()
            );

        $pictureServiceMock
            ->shouldHaveReceived("save");
        $pictureServiceMock
            ->shouldNotReceive("destroy");
    });

    it("should call save and destroy on course has url_image", function () {
        $pictureUrl = FakerMini::string(128);
        $courseRegister = container(CourseFactory::class)
            ->create([
                "url_image" => $pictureUrl
            ]);

        $courseController = container(CourseController::class);

        $requestBody = [
            "picture" => FakerMini::string(128)
        ];

        $pictureServiceMock = Mockery::mock(PictureUploadService::class);

        $pictureServiceMock->shouldReceive("save")
            ->with($requestBody["picture"])
            ->andReturn($requestBody["picture"]);

        $pictureServiceMock->shouldReceive("destroy");

        $responseBody = $courseController
            ->changePicture(
                $courseRegister->id,
                requestMock($requestBody),
                $pictureServiceMock,
                jsonResponseMock()
            );

        $pictureServiceMock
            ->shouldHaveReceived("save");
        $pictureServiceMock
            ->shouldHaveReceived("destroy", [
                $pictureUrl
            ]);
    });
});

