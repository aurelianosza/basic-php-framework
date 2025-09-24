<?php

namespace tests\Factories;

use App\Entities\User;
use tests\Utils\FakerMini;

class UserFactory {
    
    public function create(array $data = []): User
    {
        return User::repository()
            ->insert($this->make($data));
    }

    public function make(array $data = []): array
    {
        return [
            "name" => FakerMini::name(),
            "email" => FakerMini::email(),
            "registration" => FakerMini::number(),
            ...$data
        ];
    }
}
