<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy\Profile;

use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Profile;
use Symfony\Component\HttpFoundation\Request;

class ProfileTest extends \PHPUnit_Framework_TestCase
{
    const PROFILE_CLASS = 'ANU\Bundle\StyleProxyBundle\Proxy\Profile\Profile';

    /**
     * Profile object can be created with JSON string.
     */
    public function testCreateString()
    {
        $profile = new Profile(array(), $this->buildProfileJSON());
        $this->assertInstanceOf(self::PROFILE_CLASS, $profile);
    }

    /**
     * Profile object is not created with invalid JSON string.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalidString()
    {
        $invalid = 'invalid:string';
        new Profile(array(), $invalid);
    }

    /**
     * Profile object can be created with an array.
     */
    public function testCreateArray()
    {
        $profile = new Profile(array(), $this->buildProfileData());
        $this->assertInstanceOf(self::PROFILE_CLASS, $profile);
    }

    /**
     * Profile object is not created with invalid data.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalid()
    {
        new Profile(array(), false);
    }

    /**
     * Profile object contains query.
     *
     * @depends testCreateArray
     */
    public function testGetQuery()
    {
        $query = array('test' => 'value');
        $profile = new Profile(array('test' => 'value'), array());
        $this->assertEquals($query, $profile->getQuery());
    }

    /**
     * Profile returns data.
     *
     * @depends testCreateArray
     */
    public function testGetData()
    {
        $data = $this->buildProfileData();
        $profile = new Profile(array(), $data);
        $this->assertEquals($data, $profile->getData());
    }

    /**
     * Profile returns serialized JSON profile data.
     *
     * @depends testCreateArray
     */
    public function testGetSerializedData()
    {
        $data = $this->buildProfileData();
        $profile = new Profile(array(), $data);
        $this->assertEquals($data, json_decode($profile->getSerializedData(), true));
    }

    /**
     * Profile returns attribute value.
     *
     * @depends testCreateArray
     */
    public function testGet()
    {
        $data = $this->buildProfileData();
        $profile = new Profile(array(), $data);
        $this->assertEquals('www.anu.edu.au', $profile->get('site'));
    }

    /**
     * Profile returns a site variable.
     *
     * @depends testCreateArray
     */
    public function testGetSiteVariable()
    {
        $data = $this->buildProfileData();
        $profile = new Profile(array(), $data);
        $this->assertEquals('www.anu.edu.au', $profile->getSiteVariable('site_url'));
    }

    /**
     * Profile attribute value is updated.
     *
     * @depends testGet
     */
    public function testSet()
    {
        $data = $this->buildProfileData();
        $profile = new Profile(array(), $data);
        $profile->set('site', 'example.com');
        $this->assertEquals('example.com', $profile->get('site'));
    }

    /**
     * Updating profile 'meta' component arrays updates the 'meta' attribute.
     *
     * @depends testSet
     */
    public function testSetMeta()
    {
        $profile = new Profile(array(), array());
        $profile->set('meta_arr', array('line1', 'line2'));
        $this->assertEquals("line1\nline2", $profile->get('meta'));
    }

    /**
     * Profile creates a base URL from its state.
     *
     * @depends testCreateArray
     */
    public function testCreateBaseUrl()
    {
        $profile = new Profile(array(), array());
        $url = $profile->createBaseUrl('http://localhost')->getUrl();
        $this->assertEquals('http://localhost', $url);
        $url = $profile->createBaseUrl('https://localhost')->getUrl();
        $this->assertEquals('http://localhost', $url);
        $profile = new Profile(array(), array(
            'sitevars' => array('https' => true),
        ));
        $url = $profile->createBaseUrl('http://localhost')->getUrl();
        $this->assertEquals('https://localhost', $url);
    }

    /**
     * Profile creates a base URL from its state and a request context.
     *
     * @depends testCreateArray
     * @depends testCreateBaseUrl
     */
    public function testCreateBaseUrlWithContext()
    {
        $request = Request::create('http://styleproxy');
        $profile = new Profile(array(), array(), $request);
        $url = $profile->createBaseUrl()->getUrl();
        $this->assertEquals('http://styleproxy', $url);
    }

    /**
     * Returns profile JSON string.
     */
    protected function buildProfileJSON()
    {
        return file_get_contents(__DIR__.'/demoprofile.json');
    }

    protected function buildProfileData()
    {
        $data = json_decode($this->buildProfileJSON(), true);
        return $data;
    }
}
