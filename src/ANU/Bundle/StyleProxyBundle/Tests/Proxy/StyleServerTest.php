<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy;

use ANU\Bundle\StyleProxyBundle\Proxy\StyleServer;
use Symfony\Component\HttpFoundation\Request;

class StyleServerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Style server proxy forwards a style request.
     */
    public function testDelegate()
    {
        $server = new StyleServer('http://styles.anu.edu.au/_anu', 'include.php');
        $request = new Request(array(
            'id' => 1999,
            'part' => 'site',
        ));
        $response = $server->delegate($request);
        $this->assertTrue($response->isOk());
    }
}
