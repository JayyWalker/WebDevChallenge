<?php

namespace App\Handler;

use App\Environment;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PhpErrorHandler
{
    protected $environment;

    protected $code = 500;

    public function __construct(
        Environment $environment
    ) {
        $this->environment = $environment;
    }

    public function __invoke(
        Request $request,
        Response $response,
        \Throwable $throwable
    ) {
        $currentEnvironment = $this->environment->getCurrentEnvironment();
        if ($currentEnvironment === Environment::DEV) {
            $culprit = $throwable->getTrace()[0];

            $content = json_encode(
                [
                    'error' => [
                        'http_status' => $this->code,
                        'message'     => $throwable->getMessage(),
                    ]
                ]
            );
        } else if (
            $currentEnvironment === Environment::LIVE || 
            $currentEnvironment === Environment::STAGING
        ) {
            $content = json_encode(
                [
                    'error' => [
                        'http_status' => $this->code,
                        'message'     => 'Error on the server',
                    ]
                ]
            );
            error_log($throwable->getMessage());
        }

        return $response
            ->withStatus($this->code)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($content));
    }
}

