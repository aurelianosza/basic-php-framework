<?php

namespace Core\Validation\Rules;

class Required extends BaseRule {
 
    public function validate(mixed $rawValue): bool
    {
        return !is_null($rawValue) &&
            trim((string) $rawValue) !== '';
    }

    public function messageError(): string
    {
        return "Cant be null.";
    }

}
