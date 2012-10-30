<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy\Profile;

use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfilePreprocess;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Profile;
use ANU\Bundle\StyleProxyBundle\Tests\Proxy\Profile\Fixtures\ProfilePreprocess as TestProfilePreprocess;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class ProfilePreprocessTest extends WebTestCase
{
    /**
     * A profile preprocess handler can be instantiated.
     */
    public function testCreate()
    {
        $preprocess = new ProfilePreprocess(new Container(), array());
        $this->assertInstanceOf('ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfilePreprocess', $preprocess);
    }

    /**
     * A profile preprocess handler sorts preprocessors according to priority.
     *
     * @depends testCreate
     */
    public function testSortPreprocessors()
    {
        $preprocess = new TestProfilePreprocess(new Container(), array());
        $info = $preprocess->getPreprocessors(array(
            'a' => array(),
            'b' => array('priority' => 1),
            'c' => array('priority' => -1),
            'd' => array(),
        ));
        $this->assertEquals(array('c', 'a', 'd', 'b'), $info);
    }

    /**
     * A profile preprocess handler preprocesses a profile using registered preprocessors.
     *
     * @depends testCreate
     */
    public function testPreprocess()
    {
        $container = $this->createClient(array('environment' => 'test2'))->getContainer();
        $preprocess = new ProfilePreprocess($container, array(
            'anu_style_proxy.test_profile_preprocessor' => array(),
        ));
        $profile = new Profile(array('test' => 'value'), array());
        $preprocess->preprocess($profile);
        $this->assertEquals('preprocess', $profile->get('test_profile_preprocessor'));
    }
}
