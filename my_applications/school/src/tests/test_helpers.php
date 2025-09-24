<?php

use App\Responses\JsonResponse;
use Core\Container;
use Core\Http\{Request, Response};
use tests\Utils\FakerMini;

function container(string $className)
{
    return Container::getInstance()
        ->resolve($className);
}

function requestMock(?array $requestData = []): Request {
    return new Request($requestData);
}


function responseMock() {
    return new Response();
}

function jsonResponseMock() {
    return new JsonResponse();
}
