<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy\Profile;

use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Profile;

class ProfileTest extends \PHPUnit_Framework_TestCase
{
    private $profileClass = 'ANU\\Bundle\\StyleProxyBundle\\Proxy\\Profile\\Profile';

    /**
     * Profile object can be created with JSON string.
     */
    public function testCreateString()
    {
        $profile = new Profile(array(), $this->buildProfileJSON());
        $this->assertInstanceOf($this->profileClass, $profile);
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
        $this->assertInstanceOf($this->profileClass, $profile);
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
     * Profile data can be retrieved.
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
     * Serialized JSON profile data can be retrieved.
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
