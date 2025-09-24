<?php

namespace Core\Validation\Rules;

class InArray extends BaseRule {
 
    public function __construct(private array $validValues)
    {}

    public function validate(mixed $rawValue): bool
    {
        return in_array($rawValue, $this->validValues);
    }

    public function messageError(): string
    {
        return "This value invalid to field.";
    }

}
