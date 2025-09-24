<?php

namespace App\Exceptions;

use Core\Http\Response;
use Core\RenderableException;
use DomainException;

class ClassDisabledException extends DomainException implements RenderableException {

    public function __construct()
    {
        parent::__construct("Domain Exception");
    }

    public function render()
    {
        return (new Response())
            ->withHeader("Content-Type", "application/json; charset=utf8")
            ->withStatus(409)
            ->withData([
                "message" => "This class not available anymore."
            ])
            ->respond();
    }
}
