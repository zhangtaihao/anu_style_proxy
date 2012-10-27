<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CacheTest extends WebTestCase
{
    /**
     * Default cache service is transparently aliased.
     */
    public function testCacheService()
    {
        $client = $this->createClient();
        $cache = $client->getContainer()->get('anu_style_proxy.cache');
        $this->assertInstanceOf('Doctrine\\Common\\Cache\\Cache', $cache);
    }
}
