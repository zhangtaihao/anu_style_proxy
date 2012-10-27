<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Message\Response as GuzzleResponse;

/**
 * Simple proxy delegate to forward requests and responses.
 */
class SimpleProxy extends BackendDelegate
{
    protected function createBackendRequest(Request $request)
    {
        // Convert HTTP request to backend request.
        $method = $request->getMethod();
        $uri = $request->getPathInfo();
        $uri .= !is_null($qs = $request->getQueryString()) ? '?'.$qs : '';
        $headers = $request->request->all();
        if (!$headers) {
            $headers = null;
        }
        $content = $request->getContent();
        return $this->getBackendClient()->createRequest($method, $uri, $headers, $content);
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
