<?php

namespace App\Handler;

use App\Environment;
use Throwable;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ErrorHandler 
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
        Throwable $throwable
    ) {
        $currentEnvironment = $this->environment->getCurrentEnvironment();
        switch($currentEnvironment) {
            case(Environment::DEV):
                $culprit = $throwable->getTrace()[0];

                $content = json_encode(
                    [
                        'error' => [
                            'http_status' => $this->code,
                            'message'     => $throwable->getMessage(),
                            'file'        => $throwable->getFile(),
                            'culprit' => [
                                'file' => $culprit['file'],
                                'line' => $culprit['line'],
                                'args' => $culprit['args']
                            ]
                        ]
                    ]
                );
                break;
            case(Environment::STAGING):
            case(Environment::LIVE):
                $content = json_encode(
                    [
                        'error' => [
                            'http_status' => $this->code,
                            'message'     => 'Error on the server',
                        ]
                    ]
                );
                error_log($throwable->getMessage());
                break;
        }

        return $response
            ->withStatus($this->code)
            ->withHeader('Content-Type', 'application/json')
            ->write($content);
    }
}

