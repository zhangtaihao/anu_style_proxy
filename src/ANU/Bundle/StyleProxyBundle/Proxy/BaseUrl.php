<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy;

use Symfony\Component\HttpFoundation\Request;

/**
 * Base URL wrapper for the current request.
 *
 * This uses the request state to manipulate the base URL before returning.
 */
class BaseUrl
{
    protected $request;
    protected $baseUrl;
    protected $unschemedBaseUrl;

    /**
     * Wraps a base URL.
     *
     * @param Request $request
     *   Request to modify base URL with.
     * @param string|null $baseUrl
     *   Base URL to wrap. If not specified, the request base URL is used.
     */
    public function __construct(Request $request, $baseUrl = null)
    {
        $this->request = $request;
        $this->baseUrl = $baseUrl;

        if (isset($baseUrl)) {
            @list($scheme, $unschemedBaseUrl) = explode('://', $baseUrl, 2);
            if (in_array($scheme, array('http', 'https'))) {
                $this->unschemedBaseUrl = $unschemedBaseUrl;
            }
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
        elseif (isset($this->baseUrl)) {
            return $this->baseUrl;
        }
        else {
            return $this->request->getUriForPath('');
        }
    }
}
