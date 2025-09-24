<?php

namespace App\Entities;

use Core\Database\Entity;

class UserClass extends Entity {

    public static string $table = "user_class";

    public string $user_id;
    public string $class_id;
    public string $registered_at;
}
