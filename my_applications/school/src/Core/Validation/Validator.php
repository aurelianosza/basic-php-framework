<?php

namespace Core\Validation;

use Core\Validation\Exception\ValidationException;
use Core\Validation\Rules\BaseRule;

class Validator {

    private array $messageErrors = [];

    public static function make(array $data, array $rules): Validator
    {
        return new Validator($data, $rules);
    }

    private function __construct(
        private array $data,
        private array $rules
    )
    {}

    public function validate(): array
    {
        if (count($this->rules) === 0) {
            return [];
        }

        foreach ($this->rules as $fieldPath => $setOfRules) {
            $rawValue = dot_get($this->data, $fieldPath);
            foreach ($setOfRules as $rule) {
                $validationRule = BaseRule::makeRule($rule);

                if (!$validationRule->validate($rawValue)) {
                    if (!isset($this->messageErrors[$fieldPath])) {
                        $this->messageErrors[$fieldPath] = [];
                    }

                    $ruleName = $rule instanceof BaseRule
                        ? $rule->getRuleName()
                        : explode(":", $rule)[0];

                    $this->messageErrors[$fieldPath][$ruleName] = $validationRule->messageError();
                }
            }
        }

        if (count($this->messageErrors) > 0) {
            throw new ValidationException("Some field are invalid", $this->messageErrors);
        }

        return pick($this->data, array_keys($this->rules));
    }
}
