<?php

namespace Core\Http;

class Response {
    
    private array $headers = [];
    private $statusCode = 200;
    private $content;

    public function withHeader(string $headerKey, string $headerValue): self
    {
        $this->headers[$headerKey] = $headerValue;
        return $this;
    }

    public function withData($content): self
    {
        $this->content = $content;
        return $this;
    }

    public function respond()
    {
        http_response_code($this->statusCode);
        $this->renderHeaders();
        return $this->content;
    }

    public function  withStatus(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    private function renderHeaders(): void
    {
        foreach ($this->headers as $key => $header) {
            header("$key: $header");
        }
    }
}
