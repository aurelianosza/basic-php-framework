<?php

namespace Core\Http;

use Core\Validation\Validator;

class Request {

    public static function mountRequest(): Request
    {
        $headers = getallheaders();

        if ($headers["Content-Type"] === "application/json") {
            $jsonRequestData = json_decode(file_get_contents('php://input'), true);

            return new Request($jsonRequestData ?? []);
        }

        return new Request(
            array_merge($_GET, $_POST)
        );
    }

    public function __construct(private array $requestData)
    {}

    public function all(): array
    {
        return $this->requestData;    
    }

    public function input(string $path, $default = null): int|string|null|array
    {
        return dot_get($this->requestData, $path, $default);
    }

    public function validate(array $rules): array
    {
        $validation = Validator::make($this->all(), $rules);

        return $validation->validate();
    }
}
