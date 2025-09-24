<?php

namespace Core\Validation\Rules;

class MaxLength extends BaseRule {
 
    public function __construct(private int $max)
    {}

    public function validate(mixed $rawValue): bool
    {
        return strlen((string)$rawValue) <= $this->max;
    }

    public function messageError(): string
    {
        return "Should has max $this->max characters.";
    }
}
