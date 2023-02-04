<?php

namespace Shisa\Sofi\HTTP;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * The lifecycle of a request
 */
class Lifecycle
{
    /**
     * Initialize the request, you can modify your request here
     * 
     * You should never throw an exception here
     */
    public function initializeRequest(Request $request): Request
    {
        return $request;
    }

    /**
     * Prepare the request, same as `initializeRequest`
     * 
     * You can throw an exception here and the exception will be handled by
     * `handleException` method
     */
    public function prepareRequest(Request $request): Request
    {
        return $request;
    }

    /**
     * Do something before the request being processed.
     * 
     * If it returns a response, the following procedures will be ignored and
     * the response will be directly sent to `preResponse` method.
     * 
     * Exception thrown in this stage will be handled by `handleException` method
     */
    public function preRequest(Request $request): ?Response
    {
        return null;
    }

    /**
     * After the request successfully being processed in your business code,
     * you can modify your response here.
     * 
     * Exception thrown in this stage will be handled by `handleException` method
     */
    public function postRequest(Request $request, Response $response): Response
    {
        return $response;
    }

    /**
     * You can modify the response returned in `preRequest`, `postRequest` or
     * `handleException` before sending to upstream handler.
     * 
     * Exceptions should never be thrown in this stage.
     */
    public function preResponse(Request $request, Response $response): Response
    {
        return $response;
    }

    /**
     * Handling the exception throws by `prepareRequest`, `preRequest`, your
     * business and `postRequest`.
     * 
     * Throw the exception if you want it to be handled by upstream handlers.
     * 
     * @param Response $response You should create your own if response not
     *                           being passed by parameter
     */
    public function handleException(
        Request $request,
        \Exception $e,
        ?Response $response = null
    ): Response {
        throw $e;
    }

    /**
     * Do something after finishing processing the request.
     * 
     * Never throw an exception here.
     * 
     * @param ?Response $response The response object will be passed only if
     *                            the request has been successfully handled
     * @param ?\Exception $e The exception happened in the whole procedure,
     *                       either being handled or not
     */
    public function finalize(
        Request $request,
        ?Response $response = null,
        ?\Exception $e = null
    ) {
    }

    /**
     * The procedure of processing the request
     */
    protected final function invoke(
        Request $request,
        RequestHandler $handler
    ): Response {
        try {
            $request = $this->initializeRequest($request);

            try {
                $request = $this->prepareRequest($request);
                $response = $this->preRequest($request);
                if (!$response) {
                    $response = $handler->handle($request);
                    $response = $this->postRequest($request, $response);
                }
            } catch (\Exception $e) {
                $response = $this->handleException(
                    $request,
                    $e,
                    isset($response) ? $response : null,
                );
            }

            $finalResponse = $this->preResponse($request, $response);
            return $finalResponse;
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $this->finalize(
                $request,
                isset($finalResponse) ? $finalResponse : null,
                isset($e) ? $e : null
            );
        }
    }
}
