<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy\Profile;

use Doctrine\Common\Cache\ArrayCache;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfileHandler;

class ProfileHandlerTest extends \PHPUnit_Framework_TestCase
{
    const HANDLER_CLASS = 'ANU\\Bundle\\StyleProxyBundle\\Proxy\\Profile\\ProfileHandler';
    const PROFILE_CLASS = 'ANU\\Bundle\\StyleProxyBundle\\Proxy\\Profile\\Profile';

    /**
     * Profile handler can be created with cache.
     */
    public function testCreate()
    {
        $cache = new ArrayCache();
        $handler = new ProfileHandler($cache);
        $this->assertInstanceOf(self::HANDLER_CLASS, $handler);
    }

    /**
     * Profile handler does not return non-existent profile.
     *
     * @depends testCreate
     * @expectedException \ANU\Bundle\StyleProxyBundle\Exception\ProfileNotFoundException
     */
    public function testGetNonexistentProfile()
    {
        $handler = new ProfileHandler(new ArrayCache());
        $handler->getProfile(array('test' => 'value'));
    }

    /**
     * Profile handler creates an uncached profile.
     *
     * @depends testCreate
     */
    public function testCreateProfile()
    {
        $cache = $this->getMock('Doctrine\\Common\\Cache\\ArrayCache');
        $cache->expects($this->never())->method('save');
        $handler = new ProfileHandler($cache);
        $profile = $handler->createProfile(array('test' => 'value'), array('profile' => 'data'), false);
        $this->assertInstanceOf(self::PROFILE_CLASS, $profile);
    }

    /**
     * Profile handler creates a cached profile.
     *
     * @depends testCreate
     */
    public function testCreateProfileCache()
    {
        $handler = new ProfileHandler(new ArrayCache());
        $query = array('test' => 'value');
        $profile = $handler->createProfile($query, array('profile' => 'data'));
        $this->assertInstanceOf(self::PROFILE_CLASS, $profile);
        $this->assertEquals($profile->getSerializedData(), $handler->getProfile($query)->getSerializedData());
    }
}
