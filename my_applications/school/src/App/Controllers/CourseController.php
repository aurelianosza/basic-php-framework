<?php

namespace App\Controllers;

use App\Entities\Course;
use App\Responses\JsonResponse;
use App\Services\PictureService\PictureServiceInterface;
use App\Services\PictureService\PictureUploadService;
use Core\Http\{Controller, Request, Response};
use Core\Validation\Rules\InArray;

class CourseController extends Controller {
    
    public function index(Request $request, JsonResponse $jsonResponse)
    {
        $courseQuery = Course::repository();

        $filterTitle = $request->input("title", false);
        if ($filterTitle) {
            $courseQuery = $courseQuery
                ->where("title", "LIKE", "%$filterTitle%");
        }

        $filterTheme = $request->input("theme", false);
        if ($filterTheme) {
            $courseQuery = $courseQuery
                ->where("theme", $filterTheme);
        }

        $courses = $courseQuery->fetch();
            
        return $jsonResponse
            ->withData($courses)
            ->respond();
    }

    public function store(Request $request, JsonResponse $jsonResponse)
    {
        $request->validate([
            "title" => ["required", "min_length:5", "max_length:128"],
            "description" => ["required", "min_length:20", "max_length:512"],
            "theme" => ["required", new InArray(Course::THEMES)],
        ]);

        $course = Course::repository()
            ->insert([
                "title" => $request->input("title"),
                "description" => $request->input("description"),
                "theme" => $request->input("theme"),
                "url_image" => $request->input("url_image")
            ]);

        return $jsonResponse
            ->withStatus(201)
            ->withData($course)
            ->respond();
    }

    public function show(int $courseId, JsonResponse $jsonResponse,)
    {
        $course = Course::repository()
            ->where("id", $courseId)
            ->first();

        if (!$course) {
            return $jsonResponse
                ->respondNotFound();
        }

        return $jsonResponse
            ->withData($course)
            ->respond();
    }

    public function update(Request $request, int $courseId, JsonResponse $jsonResponse)
    {
        $request->validate([
            "title" => ["required", "min_length:5", "max_length:128"],
            "description" => ["required", "min_length:20", "max_length:512"],
            "theme" => ["required", new InArray(Course::THEMES)],
        ]);

        $course = Course::repository()
            ->where("id", $courseId)
            ->first();

        if (!$course) {
            return $jsonResponse
                ->respondNotFound();
        }

        $course->update([
            "title" => $request->input("title"),
            "description" => $request->input("description"),
            "theme" => $request->input("theme"),
            "url_image" => $request->input("url_image")
        ]);

        return $jsonResponse
            ->withData($course)
            ->respond();
    }

    public function changePicture(
        int $courseId,
        Request $request,
        PictureServiceInterface $coursePictureRepository,
        JsonResponse $jsonResponse
    ) {
        /** @var App\Entities\Course */
        $course = Course::repository()
            ->where("id", $courseId)
            ->first();

        if (!$course) {
            return $jsonResponse
                ->respondNotFound();
        }

        if ($course->url_image) {
            $coursePictureRepository->destroy($course->url_image);
        }

        $picturePath = $coursePictureRepository->save($request->input("picture"));

        $course->update([
            "url_image" => $picturePath
        ]);

        return $jsonResponse
            ->withData([
                "picture" => $picturePath
            ])
            ->respond();
    }

    public function destroy(int $courseId, JsonResponse $jsonResponse)
    {
        $course = Course::repository()
            ->where("id", $courseId)
            ->first();

        if (!$course) {
            return $jsonResponse
                ->respondNotFound();
        }

        $course->delete();

        return $jsonResponse
            ->withData($course)
            ->respond();
    }
}
