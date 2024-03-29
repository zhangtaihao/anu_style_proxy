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
        $client->request('GET', '/include.php?part=json');
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
     * Style server controller responds as not found for an invalid style part.
     */
    public function testIncludeInvalid()
    {
        $client = static::createClient();
        $client->request('GET', '/include.php?part=invalid');
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    /**
     * Style server controller returns an aggregated style sheet when processing.
     */
    public function testAggregate()
    {
        $client = static::createClient();
        $client->request('GET', '/include.php?part=meta');
        $this->assertContains('href="http://localhost/style_', $client->getResponse()->getContent());
    }

    /**
     * Style server controller does not aggregate style sheets if not processing.
     */
    public function testNoAggregate()
    {
        $client = static::createClient(array('environment' => 'test2'));
        $client->request('GET', '/include.php?part=meta');
        $this->assertNotContains('href="http://localhost/style_', $client->getResponse()->getContent());
    }
}
