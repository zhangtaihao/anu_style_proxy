<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy;

use Guzzle\Http\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Message\RequestInterface as GuzzleRequestInterface;
use Guzzle\Http\Message\Response as GuzzleResponse;

/**
 * Delegate style server.
 */
class StyleServer
{
    private $backendStyleBase;
    private $backendStyleInclude;

    /** @var Client */
    private $client;

    public function setBackendStyleBase($baseUrl)
    {
        $this->backendStyleBase = $baseUrl;
        if (isset($this->client)) {
            $this->client->setBaseUrl($baseUrl);
        }
    }

    public function getBackendStyleBase()
    {
        return $this->backendStyleBase;
    }

    public function setBackendStyleInclude($backendStyleInclude)
    {
        $this->backendStyleInclude = $backendStyleInclude;
    }

    public function getBackendStyleInclude()
    {
        return $this->backendStyleInclude;
    }

    /**
     * Returns the HTTP client for the backend style server.
     * @return Client
     */
    public function getBackendClient()
    {
        if (!isset($this->client)) {
            $this->client = new Client($this->backendStyleBase);
        }
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
    protected function createBackendRequest(Request $request)
    {
        $uri = $this->backendStyleInclude.'?'.$request->getQueryString();
        $backendRequest = $this->getBackendClient()->createRequest(GuzzleRequestInterface::GET, $uri);
        return $backendRequest;
    }

    /**
     * Creates a Symfony framework response from a backend response.
     *
     * @param \Guzzle\Http\Message\Response $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createResponse(GuzzleResponse $response)
    {
        return Response::create($response->getBody(), $response->getStatusCode(), $response->getHeaders()->getAll());
    }
}
