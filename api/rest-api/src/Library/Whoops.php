<?php

namespace App\Library;

use Whoops\Run as WhoopsRun;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;

class Whoops
{
    protected $request;

    protected $exception;

    public function __construct($request, $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }

    public function json($addTrace = false)
    {
        $request = $this->request;

        $handler = new JsonResponseHandler;

        if ($addTrace === true) {
            $handler->addTraceToOutput(true);
        }

        $whoops = new WhoopsRun;
        $whoops->pushHandler($handler);
        $whoops->register();

        return $whoops->handleException($this->exception);
    }

    public function prettyPage() 
    {
        $request = $this->request;

        $handler = new PrettyPageHandler;
        $handler->addDataTable('Slim Application',
            [
                'Application Class' => explode('\\', __NAMESPACE__)[0],
                'Script Name'       => $request->getServerParams()['SCRIPT_FILENAME'],
                'Request URI'       => $request->getServerParams()['REQUEST_URI'] ?: '<none>'
            ]
        );
        $handler->addDataTable('Slim Application (Request)', 
            [
                'Accept Charset'  => $request->getHeader('ACCEPT_CHARSET') ?: '<none>',
                'Content Charset' => $request->getContentCharset() ?: '<none>',
                'Path'            => $request->getUri()->getPath(),
                'Query String'    => $request->getUri()->getQuery() ?: '<none>',
                'HTTP Method'     => $request->getMethod(),
                'Base URL'        => (string) $request->getUri(),
                'Scheme'          => $request->getUri()->getScheme(),
                'Port'            => $request->getUri()->getPort(),
                'Host'            => $request->getUri()->getHost(),
            ]
        );

        $whoops = new WhoopsRun;
        $whoops->pushHandler($handler);
        $whoops->register();

        ob_start();
            $whoops->handleException($this->exception);
            $content = ob_get_content();
        ob_end_clean();

        return $content;
    }
}
