<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy;

use ANU\Bundle\StyleProxyBundle\Proxy\SimpleProxy;
use Symfony\Component\HttpFoundation\Request;

class SimpleProxyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Simple proxy directly forwards a request.
     */
    public function testDelegate()
    {
        $proxy = new SimpleProxy('http://example.com/');
        $response = $proxy->delegate(Request::create('/'));
        $this->assertTrue($response->isOk());
    }
}
