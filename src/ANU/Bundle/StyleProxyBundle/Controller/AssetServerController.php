<?php

namespace ANU\Bundle\StyleProxyBundle\Controller;

use ANU\Bundle\StyleProxyBundle\Proxy\AssetServer;
use ANU\Bundle\StyleProxyBundle\Exception\UnsupportedResourceException;
use ANU\Bundle\StyleProxyBundle\Proxy\BaseUrl;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class AssetServerController extends Controller
{
    protected $backendBaseUrl;

    protected $cacheMaxAge;

    public function __construct(BaseUrl $backendBaseUrl)
    {
        $this->backendBaseUrl = $backendBaseUrl;
    }

    public function setResponseCache($enabled, $maxAge)
    {
        if (!$enabled) {
            $this->cacheMaxAge = 0;
        }
        elseif (is_int($maxAge)) {
            $this->cacheMaxAge = $maxAge;
        }
    }

    public function resourceAction($path)
    {
        $request = $this->getRequest();

        // Delegate to asset server to retrieve backend.
        if ($this->has('anu_style_proxy.asset_server')) {
            /** @var $server AssetServer */
            $server = $this->get('anu_style_proxy.asset_server');
            try {
                $response = $server->getResource($request);

                // Cache response.
                if ($this->cacheMaxAge) {
                    $response->setPublic();
                    $response->setMaxAge($this->cacheMaxAge);
                    $response->headers->addCacheControlDirective('must-revalidate', true);
                }

                return $response;
            }
            catch (UnsupportedResourceException $e) {}
        }

        // Redirect to backend by default.
        return $this->forward('anu_style_proxy.asset_server_controller:redirectAction', array(
            'path' => $path,
        ));
    }

    /**
     * @Cache(maxage="86400")
     */
    public function redirectAction($path)
    {
        $url = rtrim($this->backendBaseUrl->getUrl(), '/').'/'.$path;
        return $this->redirect($url, 301);
    }
}
