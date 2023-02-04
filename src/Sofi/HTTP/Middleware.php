<?php

namespace Shisa\Sofi\HTTP;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Middleware extends Lifecycle
{
    public final function __invoke(
        Request $request,
        RequestHandler $handler
    ): Response {
        return $this->invoke($request, $handler);
    }
}
