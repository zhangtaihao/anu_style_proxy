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
class StyleServer extends SimpleProxy
{
    private $includePath;

    public function __construct($baseUrl, $includePath)
    {
        parent::__construct($baseUrl);
        $this->includePath = $includePath;
    }

    /**
     * {@inheritdoc}
     */
    protected function createBackendRequest(Request $request)
    {
        $uri = $this->includePath.'?'.$request->getQueryString();
        $backendRequest = $this->getBackendClient()->createRequest(GuzzleRequestInterface::GET, $uri);
        return $backendRequest;
    }
}
