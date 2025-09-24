<?php

namespace Core\Validation\Rules;

class Min extends BaseRule {
 
    public function __construct(private int $min)
    {}

    public function validate(mixed $rawValue): bool
    {
        return (int)$rawValue >= $this->min;
    }

    public function messageError(): string
    {
        return "Should be $this->min in the minimum.";
    }
}
