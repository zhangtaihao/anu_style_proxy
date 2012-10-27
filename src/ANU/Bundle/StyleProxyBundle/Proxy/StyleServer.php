<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy;

use Guzzle\Http\Client;
use Guzzle\Http\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Guzzle\Http\Message\Response
     */
    public function delegate(Request $request)
    {
        $backendRequest = $this->createBackendRequest($request);
        return $backendRequest->send();
    }

    protected function createBackendRequest(Request $request)
    {
        $uri = $this->backendStyleInclude.'?'.$request->getQueryString();
        $backendRequest = $this->getBackendClient()->createRequest(RequestInterface::GET, $uri);
        return $backendRequest;
    }
}
