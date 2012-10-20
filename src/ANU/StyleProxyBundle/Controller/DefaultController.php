<?php

namespace ANU\StyleProxyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ANUStyleProxyBundle:Default:index.html.twig', array('name' => $name));
    }
}
