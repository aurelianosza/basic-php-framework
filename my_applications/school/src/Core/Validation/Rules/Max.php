<?php

namespace Core\Validation\Rules;

class Max extends BaseRule {
 
    public function __construct(private int $max)
    {}

    public function validate(mixed $rawValue): bool
    {
        return (int)$rawValue <= $this->max;
    }

    public function messageError(): string
    {
        return "Should be $this->max in the max.";
    }
}
