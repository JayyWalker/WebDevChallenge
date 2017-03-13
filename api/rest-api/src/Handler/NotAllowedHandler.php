<?php

namespace App\Handler;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class NotAllowedHandler
{
    public function __invoke(
        Request $request,
        Response $response,
        $methods
    ) {
        return $response
            ->withStatus(405)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode(
                [
                    'error' => [
                        'http_code'       => 405,
                        'request_method'  => $request->getMethod(),
                        'message'         => 'Method not allowed on resource',
                        'allowed_methods' => $methods
                    ]
                ]
            ));
        }
}
