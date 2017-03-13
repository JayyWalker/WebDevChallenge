<?php

namespace App\Handler;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class NotFoundHandler
{
    public function __invoke(
        Request $request,
        Response $response
    ) {
        return $response
            ->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode(
                [
                    'error' => [
                        'http_code' => 404,
                        'message'   => 'Resource not found'
                    ]
                ]
            ));
    }
}
