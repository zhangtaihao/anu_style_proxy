<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy;

use Symfony\Component\HttpFoundation\Request;

/**
 * Wrapper for the current request.
 *
 * This uses the request state to manipulate the base URL before returning.
 */
class BaseUrl
{
    protected $baseUrl;
    protected $unschemedBaseUrl;
    protected $request;

    public function __construct($baseUrl, Request $request)
    {
        $this->baseUrl = $baseUrl;
        $this->request = $request;

        @list($scheme, $unschemedBaseUrl) = explode('://', $baseUrl, 2);
        if (in_array($scheme, array('http', 'https'))) {
            $this->unschemedBaseUrl = $unschemedBaseUrl;
        }
    }

    /**
     * Returns the URL for the current request.
     */
    public function getUrl()
    {
        if (isset($this->unschemedBaseUrl)) {
            return $this->request->getScheme().'://'.$this->unschemedBaseUrl;
        }
        else {
            return $this->baseUrl;
        }
    }
}
