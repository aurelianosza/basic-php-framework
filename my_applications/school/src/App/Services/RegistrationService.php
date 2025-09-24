<?php

namespace App\Services;

use DateTime;

class RegistrationService {

    public function generateRegistrationFomCurrentDate(): string
    {
        return (new DateTime("now"))
            ->format("YmdHis");
    }

}
