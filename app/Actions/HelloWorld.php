<?php

namespace App\Actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Shisa\Sofi\HTTP\Action;

final class HelloWorld extends Action
{
    public function handleRequest(
        Request $request,
        Response $response,
        $args
    ): Response {
        $name = $args['name'] ?: 'Shisa';
        $message = sprintf(_("Hello, %s"), $name);
        $response->getBody()->write($message);
        return $response;
    }
}
