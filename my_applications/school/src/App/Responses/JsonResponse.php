<?php

namespace App\Responses;

use Core\Http\Response;

class JsonResponse extends Response {

    public function __construct() {
        $this->withHeader(
            "Content-Type",
            "application/json; charset=utf8"
        );
    }

    public function respondNotFound()
    {
        $returnBody = $this->withStatus(404)
            ->withData([
                "message" => "Model not found"
            ]);

        return $returnBody->respond();
    }
}
