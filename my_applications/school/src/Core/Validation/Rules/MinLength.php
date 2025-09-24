<?php

namespace Core\Validation\Rules;

class MinLength extends BaseRule {
 
    public function __construct(private int $min)
    {}

    public function validate(mixed $rawValue): bool
    {
        return strlen((string)$rawValue) >= $this->min;
    }

    public function messageError(): string
    {
        return "Should has min $this->min characters.";
    }
}
