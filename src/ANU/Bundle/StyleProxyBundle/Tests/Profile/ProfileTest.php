<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Profile;

use ANU\Bundle\StyleProxyBundle\Profile\Profile;

class ProfileTest extends \PHPUnit_Framework_TestCase
{
    private $profileClass = 'ANU\\Bundle\\StyleProxyBundle\\Profile\\Profile';

    /**
     * Profile object can be created with JSON string.
     */
    public function testCreateString()
    {
        $profile = new Profile($this->buildProfileJSON());
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
        new Profile($invalid);
    }

    /**
     * Profile object can be created with an array.
     */
    public function testCreateArray()
    {
        $profile = new Profile($this->buildProfileData());
        $this->assertInstanceOf($this->profileClass, $profile);
    }

    /**
     * Profile object is not created with invalid data.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCreateInvalid()
    {
        new Profile(false);
    }

    /**
     * Profile data can be retrieved.
     *
     * @depends testCreateArray
     */
    public function testGetData()
    {
        $data = $this->buildProfileData();
        $profile = new Profile($data);
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
        $profile = new Profile($data);
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
