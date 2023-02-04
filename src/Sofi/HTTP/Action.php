<?php

namespace Shisa\Sofi\HTTP;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

abstract class Action extends Lifecycle
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        return $this->invoke($request, new class(
            $this,
            $request,
            $response,
            $args
        ) implements RequestHandler
        {
            public function __construct(
                private Action $action,
                private Request $request,
                private Response $response,
                private $args
            ) {
            }

            public function handle(Request $request): Response
            {
                return $this->action->handleRequest(
                    $this->request,
                    $this->response,
                    $this->args
                );
            }
        });
    }

    public abstract function handleRequest(Request $request, Response $response, $args): Response;
}
