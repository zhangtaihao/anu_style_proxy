<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy;

use ANU\Bundle\StyleProxyBundle\Proxy\StyleServer;
use Doctrine\Common\Cache\ArrayCache;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfileHandler;
use Symfony\Component\HttpFoundation\Request;

class StyleServerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Style server proxy forwards a style request.
     */
    public function testDelegate()
    {
        $handler = new ProfileHandler(new ArrayCache());
        $server = new StyleServer('http://styles.anu.edu.au/_anu', 'include.php', $handler);
        $request = new Request(array(
            'id' => 1999,
            'part' => 'site',
        ));
        $response = $server->delegate($request);
        $this->assertTrue($response->isOk());
    }
}
