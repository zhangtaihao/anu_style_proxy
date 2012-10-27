<?php

namespace ANU\Bundle\StyleProxyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use ANU\Bundle\StyleProxyBundle\Proxy\StyleServer;

class StyleServerController extends Controller
{
    public function includeAction()
    {
        $request = $this->getRequest();

        /** @var $styleServer StyleServer */
        $styleServer = $this->get('anu_style_proxy.style_server');

        return $styleServer->delegate($request);
    }
}
