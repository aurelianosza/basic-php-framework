<?php

namespace Core\Validation\Rules;

use Core\Database\BaseRepository;

class Exists extends BaseRule {
 
    public function __construct(
        private string $column,
        private BaseRepository $baseRepository)
    {}

    public function validate(mixed $rawValue): bool
    {
        if (!$rawValue) {
            return false;
        }

        $return = $this->baseRepository
            ->where($this->column, $rawValue)
            ->first();

        return $return !== null;
    }

    public function messageError(): string
    {
        return "Invalid value, should be a existing field";
    }
}
