<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy;

use ANU\Bundle\StyleProxyBundle\Proxy\StyleServer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Cache\ArrayCache;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfileHandler;

class StyleServerTest extends \PHPUnit_Framework_TestCase
{
    const SERVER_CLASS = 'ANU\\Bundle\\StyleProxyBundle\\Proxy\\StyleServer';

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

    /**
     * Style server proxy returns a cached profile for a query.
     */
    public function testGetProfile()
    {
        $handler = new ProfileHandler(new ArrayCache());
        $server = $this->getMock(self::SERVER_CLASS, array('delegate'), array('', '', $handler));
        $server->expects($this->once())
            ->method('delegate')
            ->will($this->returnValue(Response::create('{"test":"value"}')));
        /** @var $server StyleServer */
        $request = new Request(array(
            'id' => 1999,
            'part' => 'site',
        ));
        $server->getProfileForRequest($request);
        // Call again to ensure invocation only once.
        $server->getProfileForRequest($request);
    }

    /**
     * Style server proxy does not return a profile for an invalid request.
     *
     * @expectedException \ANU\Bundle\StyleProxyBundle\Exception\InvalidProfileRequestException
     */
    public function testGetInvalidProfile()
    {
        $handler = new ProfileHandler(new ArrayCache());
        $server = new StyleServer('http://styles.anu.edu.au/_anu', 'include.php', $handler);
        $request = new Request();
        $server->getProfileForRequest($request);
    }

    /**
     * Style server retrieves a style part for a request.
     *
     * @depends testGetProfile
     */
    public function testGetStyleInclude()
    {
        $handler = new ProfileHandler(new ArrayCache());
        $server = new StyleServer('http://styles.anu.edu.au/_anu', 'include.php', $handler);
        $request = new Request(array(
            'id' => 1999,
            'part' => 'site',
        ));
        $include = $server->getStyleInclude($request);
        $this->assertEquals('www.anu.edu.au', $include);
    }
}
