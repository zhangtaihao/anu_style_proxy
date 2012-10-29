<?php

namespace ANU\Bundle\StyleProxyBundle\Controller;

use ANU\Bundle\StyleProxyBundle\Proxy\AssetServer;
use ANU\Bundle\StyleProxyBundle\Proxy\BaseUrl;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AssetServerController extends Controller
{
    protected $backendBaseUrl;

    public function __construct(BaseUrl $backendBaseUrl)
    {
        $this->backendBaseUrl = $backendBaseUrl;
    }

    public function resourceAction($path)
    {
        $request = $this->getRequest();

        // Delegate to asset server to retrieve backend.
        if ($this->has('anu_style_proxy.asset_server')) {
            /** @var $server AssetServer */
            $server = $this->get('anu_style_proxy.asset_server');
            return $server->getResource($request);
        }
        // Redirect to backend.
        else {
            $url = rtrim($this->backendBaseUrl->getUrl(), '/').'/'.$path;
            return $this->redirect($url);
        }
    }
}
