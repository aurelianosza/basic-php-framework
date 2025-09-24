<?php

namespace App\Entities;

use Core\Database\Entity;
use DateTime;

class CourseClass extends Entity {

    const STATUS_DISPONIVEL = "disponível";
    const STATUS_ENCERRADO = "encerrado";
    const STATUS_DESABILITADO = "desabilitado";

    const STATUSES = [
        self::STATUS_DISPONIVEL,
        self::STATUS_ENCERRADO,
        self::STATUS_DESABILITADO
    ];

    public static string $table = "classes";
    
    public int $course_id;
    public string $title;
    public string $description;
    public int $vacancies;
    public string $status;
    public string $start_date;
    public string $end_date;

    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_DISPONIVEL;
    }

    public function isInValidPeriod(): bool
    {
        $startDate = new DateTime($this->start_date);
        $endDate = new DateTime($this->end_date);
        $currentDate = new DateTime("now");

        return $startDate < $currentDate &&
            $currentDate < $endDate;
    }
}
