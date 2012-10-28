<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StyleServerControllerTest extends WebTestCase
{
    /**
     * Style server controller responds with a style part.
     */
    public function testInclude()
    {
        $client = static::createClient();
        $client->request('GET', '/include.php?part=site');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * Style server controller responds as not found for an invalid style part.
     */
    public function testIncludeInvalid()
    {
        $client = static::createClient();
        $client->request('GET', '/include.php?part=invalid');
        $this->assertTrue($client->getResponse()->isNotFound());
    }
}
