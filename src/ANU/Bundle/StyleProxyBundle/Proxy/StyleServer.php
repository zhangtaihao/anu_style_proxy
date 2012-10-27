<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy;

use Guzzle\Http\Client;

class StyleServer
{
    protected $backendStyleBase;

    private $client;

    public function setBackendStyleBase($baseUrl)
    {
        $this->backendStyleBase = $baseUrl;
    }

    public function getBackendStyleBase()
    {
        return $this->backendStyleBase;
    }

    public function getBackendClient()
    {
        if (!isset($this->client)) {
            $this->client = new Client($this->backendStyleBase);
        }
        return $this->client;
    }
}
