<?php

namespace ANU\Bundle\StyleProxyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class DefaultController extends Controller
{
    /**
     * @Cache(maxage="2592000")
     */
    public function indexAction()
    {
        return $this->render('ANUStyleProxyBundle:Default:index.html.twig', array(
            'message' => $this->container->getParameter('anu_style_proxy.placeholder_message'),
        ));
    }
}
