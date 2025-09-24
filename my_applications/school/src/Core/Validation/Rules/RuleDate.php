<?php

namespace Core\Validation\Rules;

use DateTime;

class RuleDate extends BaseRule {
 
    public function __construct()
    {}

    public function validate(mixed $rawValue): bool
    {
        return DateTime::createFromFormat('Y-m-d', $rawValue ?? "") !== false;
    }
    
    public function messageError(): string
    {
        return "This value invalid date field.";
    }

    public function getRuleName(): string
    {
        return "date";
    }

}
