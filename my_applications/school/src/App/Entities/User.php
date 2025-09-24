<?php

namespace App\Entities;

use Core\Database\Entity;

class User extends Entity {

    public static string $table = "users";

    public string $name;
    public string $email;
    public string $registration;
}
