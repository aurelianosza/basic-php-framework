<?php

namespace Core\Validation\Rules;

class Integer extends BaseRule {

    public function validate(mixed $rawValue): bool
    {
        return filter_var($rawValue, FILTER_VALIDATE_INT);
    }

    public function messageError(): string
    {
        return "Should be a valid numeric integer value.";
    }
}
