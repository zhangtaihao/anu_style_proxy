<?php

namespace ANU\Bundle\StyleProxyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ANUStyleProxyBundle:Default:index.html.twig', array(
            'message' => $this->container->getParameter('anu_style_proxy.placeholder_message'),
        ));
    }
}
