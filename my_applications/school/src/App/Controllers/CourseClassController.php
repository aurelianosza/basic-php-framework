<?php

namespace App\Controllers;

use App\Entities\Course;
use App\Entities\CourseClass;
use App\Responses\JsonResponse;
use Core\Http\Controller;
use Core\Http\Request;
use Core\Validation\Rules\Exists;

class CourseClassController extends Controller {

    public function index(Request $request, JsonResponse $jsonResponse)
    {
        $courseQuery = CourseClass::repository();

        $courseClasses = $courseQuery->fetch();

        return $jsonResponse
            ->withData($courseClasses)
            ->respond();
    }

    public function store(Request $request, JsonResponse $jsonResponse)
    {
        $request->validate([
            "course_id" => ["required", new Exists("id", Course::repository())],
            "title" => ["required", "min_length:5", "max_length:64"],
            "description" => ["required", "min_length:20", "max_length:512"],
            "vacancies" => ["required", "integer", "min:1", "max:255"],
            "start_date" => ["required", "date"],
            "end_date" => ["required", "date"],
        ]);

        $courseClass = CourseClass::repository()
            ->insert([
                "course_id" => $request->input("course_id"),
                "title" => $request->input("title"),
                "description" => $request->input("description"),
                "status" => CourseClass::STATUS_DISPONIVEL,
                "vacancies" => $request->input("vacancies"),
                "start_date" => $request->input("start_date"),
                "end_date" => $request->input("end_date")
            ]);

        return $jsonResponse
            ->withStatus(201)
            ->withData($courseClass)
            ->respond();
    }

    public function show(int $courseClassId, JsonResponse $jsonResponse)
    {
        $courseClass = CourseClass::repository()
            ->where("id", $courseClassId)
            ->first();

        if (!$courseClass) {
            return $jsonResponse
                ->respondNotFound();
        }

        return $jsonResponse
            ->withData($courseClass)
            ->respond();
    }

    public function update(Request $request, int $courseClassId, JsonResponse $jsonResponse)
    {
        $request->validate([
            "course_id" => ["required", new Exists("id", Course::repository())],
            "title" => ["required", "min_length:5", "max_length:64"],
            "description" => ["required", "min_length:20", "max_length:512"],
            "vacancies" => ["required", "integer", "min:1", "max:255"],
            "start_date" => ["required", "date"],
            "end_date" => ["required", "date"],
        ]);

        $courseClass = CourseClass::repository()
            ->where("id", $courseClassId)
            ->first();

        if (!$courseClass) {
            return $jsonResponse
                ->respondNotFound();
        }

        $courseClass->update([
            "course_id" => $request->input("course_id"),
            "title" => $request->input("title"),
            "description" => $request->input("description"),
            "vacancies" => $request->input("vacancies"),
            "start_date" => $request->input("start_date"),
            "end_date" => $request->input("end_date")
        ]);

        return $jsonResponse
            ->withData($courseClass)
            ->respond();
    }

    public function destroy(int $courseClassId, JsonResponse $jsonResponse)
    {
        $courseClass = CourseClass::repository()
            ->where("id", $courseClassId)
            ->first();

        if (!$courseClass) {
            return $jsonResponse
                ->respondNotFound();
        }

        $courseClass->update([
            "status" => CourseClass::STATUS_DESABILITADO
        ]);

        return $jsonResponse
            ->withData($courseClass)
            ->respond();
    }
}
