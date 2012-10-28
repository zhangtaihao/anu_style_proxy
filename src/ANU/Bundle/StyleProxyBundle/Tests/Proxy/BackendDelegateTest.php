<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy;

use ANU\Bundle\StyleProxyBundle\Proxy;
use ANU\Bundle\StyleProxyBundle\Tests\Exception;
use Symfony\Component\HttpFoundation\Request;

class BackendDelegateTest extends \PHPUnit_Framework_TestCase
{
    const DELEGATE_CLASS = 'ANU\Bundle\StyleProxyBundle\Proxy\BackendDelegate';

    /**
     * Backend delegate creates backend HTTP client using base URL.
     */
    public function testClient()
    {
        $base = 'http://example.com/';
        $delegate = $this->getMockForAbstractClass(self::DELEGATE_CLASS, array($base));
        /** @var $delegate Proxy\BackendDelegate */
        $client = $delegate->getBackendClient();
        $this->assertInstanceOf('Guzzle\Http\Client', $client);
        $this->assertEquals($base, $client->getBaseUrl());
    }

    /**
     * Backend delegate forwards request.
     *
     * @expectedException \ANU\Bundle\StyleProxyBundle\Tests\Exception
     * @expectedExceptionMessage create request
     */
    public function testDelegateRequest()
    {
        $delegate = $this->getMockForAbstractClass(self::DELEGATE_CLASS, array(''));
        $delegate->expects($this->any())
            ->method('createBackendRequest')
            ->will($this->throwException(new Exception('create request')));
        /** @var $delegate Proxy\BackendDelegate */
        $delegate->delegate(new Request());
    }

    /**
     * Backend delegate forwards request.
     *
     * @depends testClient
     * @expectedException \ANU\Bundle\StyleProxyBundle\Tests\Exception
     * @expectedExceptionMessage create response
     */
    public function testDelegateResponse()
    {
        $delegate = $this->getMockForAbstractClass(self::DELEGATE_CLASS, array('http://localhost'));
        /** @var $delegate Proxy\BackendDelegate */
        $client = $delegate->getBackendClient();
        $delegate->expects($this->any())
            ->method('createBackendRequest')
            ->will($this->returnValue($client->createRequest('GET', '/')));
        $delegate->expects($this->any())
            ->method('createResponse')
            ->will($this->throwException(new Exception('create response')));
        /** @var $delegate Proxy\BackendDelegate */
        $delegate->delegate(new Request());
    }
}
