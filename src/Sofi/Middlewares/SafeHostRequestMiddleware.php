<?php

namespace Shisa\Sofi\Middlewares;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shisa\Sofi\Configurations\Configuration;
use Shisa\Sofi\HTTP\Middleware;

/**
 * Only allow accessing via configured hosts.
 */
class SafeHostRequestMiddleware extends Middleware
{
    public function __construct(
        private Configuration $configuration,
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    public function preRequest(Request $request): ?Response
    {
        $host = array_pop($request->getHeader('Host'));
        if (in_array($host, $this->configuration->hosts)) {
            return null;
        }
        $response = $this->responseFactory->createResponse(400);
        $this->configuration->debug
            && $response->getBody()->write('Invalid hostname: ' . $host);
        return $response;
    }
}
