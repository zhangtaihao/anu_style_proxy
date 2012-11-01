<?php

namespace ANU\Bundle\StyleProxyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use ANU\Bundle\StyleProxyBundle\Proxy\StyleServer;

class StyleServerController extends Controller
{
    protected $cacheMaxAge;

    public function setResponseCache($enabled, $maxAge)
    {
        if (!$enabled) {
            $this->cacheMaxAge = 0;
        }
        elseif (is_int($maxAge)) {
            $this->cacheMaxAge = $maxAge;
        }
    }

    public function includeAction()
    {
        $request = $this->getRequest();

        // Render status check message if style request is empty.
        if (is_null($request->getQueryString())) {
            $response = Response::create();
            // Enable daily checks.
            $response->setMaxAge(86400);
            return $response;
        }

        /** @var $styleServer StyleServer */
        $styleServer = $this->get('anu_style_proxy.style_server');

        $include = $styleServer->getStyleInclude($request);
        if (!isset($include)) {
            throw $this->createNotFoundException();
        }
        $response = Response::create($include, 200, array('Content-Type' => 'text/plain'));

        // Cache response.
        if ($this->cacheMaxAge) {
            $response->setPublic();
            $response->setMaxAge($this->cacheMaxAge);
            $response->headers->addCacheControlDirective('must-revalidate', true);
        }
        return $response;
    }
}
