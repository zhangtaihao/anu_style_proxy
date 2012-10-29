<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy;

use ANU\Bundle\StyleProxyBundle\Proxy\BaseUrl;
use Symfony\Component\HttpFoundation\Request;

class BaseUrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * A base URL wrapper can be instantiated.
     */
    public function testCreate()
    {
        $url = new BaseUrl(Request::create('http://localhost/'), 'http://example.com/');
        $this->assertInstanceOf('ANU\Bundle\StyleProxyBundle\Proxy\BaseUrl', $url);
    }

    /**
     * A base URL wrapper returns a URL for a non-secure URL.
     *
     * @depends testCreate
     */
    public function testGetUrl()
    {
        $url = new BaseUrl(Request::create('http://localhost/'), 'http://example.com/');
        $this->assertEquals('http://example.com/', $url->getUrl());
    }

    /**
     * A base URL wrapper returns a URL for a secure URL.
     *
     * @depends testCreate
     */
    public function testGetUrlSecure()
    {
        $url = new BaseUrl(Request::create('https://localhost/'), 'http://example.com/');
        $this->assertEquals('https://example.com/', $url->getUrl());
    }

    /**
     * A base URL wrapper returns an unaltered URL for a non-HTTP URL.
     *
     * @depends testCreate
     */
    public function testGetUrlNonStandard()
    {
        $url = new BaseUrl(Request::create('https://localhost/'), 'nonstandard://example.com/');
        $this->assertEquals('nonstandard://example.com/', $url->getUrl());
    }

    /**
     * A base URL wrapper returns the request URL if no base URL is used to create it.
     *
     * @depends testGetUrl
     */
    public function testGetUrlRequest()
    {
        $url = new BaseUrl(Request::create('http://localhost'));
        $this->assertEquals('http://localhost', $url->getUrl());
    }
}
