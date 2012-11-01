<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy;

use Guzzle\Http\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Message\Response as GuzzleResponse;

/**
 * Delegate server handler for a backend server base URL.
 */
abstract class BackendDelegate
{
    protected $baseUrl;
    /** @var Client */
    private $client;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->client = $this->createBackendClient($baseUrl);
    }

    /**
     * Creates a backend client.
     */
    protected function createBackendClient($baseUrl)
    {
        return new Client($baseUrl);
    }

    /**
     * Returns the HTTP client for the backend style server.
     * @return Client
     */
    public function getBackendClient()
    {
        return $this->client;
    }

    /**
     * Delegates a request to the backend server.
     *
     * @param Request $request
     * @return Response
     */
    public function delegate(Request $request)
    {
        $backendRequest = $this->createBackendRequest($request);
        $backendResponse = $backendRequest->send();
        return $this->createResponse($backendResponse);
    }

    /**
     * Creates a backend request from a Symfony framework request.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Guzzle\Http\Message\RequestInterface
     */
    abstract protected function createBackendRequest(Request $request);

    /**
     * Creates a Symfony framework response from a backend response.
     *
     * @param \Guzzle\Http\Message\Response $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    abstract protected function createResponse(GuzzleResponse $response);
}
