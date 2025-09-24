<?php

namespace Core\Validation\Rules;

class Email extends BaseRule {

    public function validate(mixed $rawValue): bool
    {
        return filter_var($rawValue, FILTER_VALIDATE_EMAIL);
    }

    public function messageError(): string
    {
        return "Should be a valid email address.";
    }
}
