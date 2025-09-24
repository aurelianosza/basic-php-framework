<?php

namespace tests\Factories;

use App\Entities\Course;
use tests\Utils\FakerMini;

class CourseFactory {

    public function create(array $data = []): Course
    {
        $courseCreated = Course::repository()
            ->insert($this->make($data));

        return $courseCreated;
    }

    public function make(array $data = []): array
    {
        return [
            "title" => FakerMini::name(),
            "description" => FakerMini::string(20),
            "theme" => FakerMini::random(Course::THEMES),
            "url_image" => FakerMini::url(),
            ...$data
        ];
    }
}
