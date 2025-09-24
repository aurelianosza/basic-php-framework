<?php

namespace Core\Validation\Exception;

use Core\Http\Response;
use Core\RenderableException;
use Exception;

class ValidationException extends Exception implements RenderableException {

    public function __construct(
        protected string $messages,
        private array $validationErrors
    )
    {
        parent::__construct($messages);    
    }

    public function render()
    {
        return (new Response())
            ->withHeader("Content-Type", "application/json; charset=utf8")
            ->withStatus(422)
            ->withData($this->validationErrors)
            ->respond();
    }
}
