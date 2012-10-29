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
    }
}
