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
     * Style server response cache is configurable.
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
     * Asset server controller redirects an asset request if not caching.
     */
    public function testResourceRedirect()
    {
        $client = static::createClient(array('environment' => 'test2'));
        $client->request('GET', '/images/icons/external.png');
        $this->assertTrue($client->getResponse()->isRedirect());
    }
}
