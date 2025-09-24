<?php

namespace App\Controllers\UseCases;

use App\Entities\Course;
use App\Entities\User;
use App\Responses\JsonResponse;
use Core\Http\Controller;
use Core\Http\Request;

class ShowUsersCourses extends Controller {

    public function execute(int $userId, JsonResponse $jsonResponse)
    {
        $user = User::repository()
            ->where("id", $userId)
            ->first();
        
        if (!$user) {
            return $jsonResponse->respondNotFound();
        }

        $courses = Course::repository()
            ->join("classes", "courses.id = classes.course_id")
            ->join("user_class", "user_class.class_id = classes.id")
            ->where("user_class.user_id", $userId)
            ->fetch();

        return  $jsonResponse
            ->withData([
                "user" => $user,
                "courses" => $courses
            ])->respond();
    }
}
