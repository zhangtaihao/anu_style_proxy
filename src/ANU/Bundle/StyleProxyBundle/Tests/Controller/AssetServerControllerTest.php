<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AssetServerControllerTest extends WebTestCase
{
    /**
     * Asset server controller responds with an asset.
     */
    public function testResource()
    {
        $client = static::createClient();
        $client->request('GET', '/images/icons/external.png');
        $this->assertTrue($client->getResponse()->isSuccessful());
        // Fetch again to trigger fetching a local resource.
        $client->request('GET', '/images/icons/external.png');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * Asset server response cache is configurable.
     */
    public function testCache()
    {
        $client = static::createClient();
        $client->request('GET', '/include.php?part=site');
        /** @var $response \Symfony\Component\HttpFoundation\Response */
        $response = $client->getResponse();
        $this->assertNotEmpty($response->headers->getCacheControlDirective('public'));
        $client = static::createClient(array('environment' => 'test2'));
        $client->request('GET', '/include.php?part=site');
        $response = $client->getResponse();
        $this->assertFalse($response->headers->hasCacheControlDirective('public'));
    }

    /**
     * Asset server controller resource action redirects an asset request if not caching.
     */
    public function testResourceRedirect()
    {
        $client = static::createClient(array('environment' => 'test2'));
        $client->request('GET', '/images/icons/external.png');
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * Asset server controller resource action redirects a unsupported resource.
     */
    public function testRedirectUnsupported()
    {
        $client = static::createClient();
        $client->request('GET', '/3/scripts/anu-common.js');
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * Asset server controller redirects a unrecognised request even if caching.
     */
    public function testUnrecognizedRedirect()
    {
        $client = static::createClient();
        $client->request('GET', '/some/unrecognized/request');
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * Asset server controller returns not found if an asset to be retrieve cannot be materialized.
     */
    public function testResourceNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/images/notfound');
        $this->assertTrue($client->getResponse()->isNotFound());
    }
}
