<?php

namespace tests\Factories;

use App\Entities\CourseClass;
use DateTime;
use tests\Utils\FakerMini;

class CourseClassFactory {

    public function create(array $data = []): CourseClass
    {
        $courseCreated = CourseClass::repository()
            ->insert($this->make($data));

        return $courseCreated;
    }

    public function make(array $data = []): array
    {
        return [
            "course_id" => FakerMini::number(),
            "title" => FakerMini::name(),
            "description" => FakerMini::string(20),
            "vacancies" => FakerMini::number(1, 255),
            "start_date" => (new DateTime())->format("Y-m-d"),
            "end_date" => (new DateTime())->format("Y-m-d"),
            "status" => FakerMini::random(CourseClass::STATUSES),
            ...$data
        ];
    }

    public function createWithCourse(array $data = []): CourseClass
    {
        $courseCreated = CourseClass::repository()
            ->insert($this->makeWithCourse($data));

        return $courseCreated;
    }

    public function makeWithCourse(array $data = []): array
    {
        $course = container(CourseFactory::class)
            ->create();
            
        return $this->make([
            "course_id" => $course->id,
            ...$data
        ]);
    }
}
