<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Preprocessor;

use ANU\Bundle\StyleProxyBundle\Preprocessor\CacheProfileAssetPreprocessor;
use Orbt\ResourceMirror\Resource\GenericResource;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Profile;
use Orbt\ResourceMirror\ResourceMirror;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CacheProfileAssetPreprocessorTest extends WebTestCase
{
    /**
     * The profile asset preprocessor caches and rewrites backend asset URLs.
     */
    public function testPreprocess()
    {
        $container = $this->createClient()->getContainer();
        /** @var $preprocessor CacheProfileAssetPreprocessor */
        $preprocessor = $container->get('anu_style_proxy.cache_profile_asset_preprocessor');

        $testContent = <<<EOF
            <link href="http://styles.anu.edu.au/_anu/3/images/logos/anu.ico" rel="shortcut icon" type="image/x-icon"/>
            <link href="http://styles.anu.edu.au/_anu/3/style/anu-common.css" rel="stylesheet" type="text/css" media="screen"/>
            <img src="http://styles.anu.edu.au/_anu/3/images/logos/anu_logo_print.png" alt="" />
EOF;
        $profile = new Profile(array(), array(
            'banner' => $testContent,
            'css_ie_arr' => array('<!--[if IE]><link href="http://styles.anu.edu.au/_anu/3/style/anu-ie.css" rel="stylesheet" type="text/css" media="screen"/><![endif]-->'),
        ));
        $preprocessor->preprocess($profile);
        $preprocessedContent = $profile->get('banner');
        $this->assertContains('href="http://localhost/3/images/logos/anu.ico"', $preprocessedContent);
        $this->assertContains('href="http://localhost/3/style/anu-common.css"', $preprocessedContent);
        $this->assertContains('src="http://localhost/3/images/logos/anu_logo_print.png"', $preprocessedContent);
        $preprocessedContent = $profile->get('css_ie_arr');
        $this->assertContains('href="http://localhost/3/style/anu-ie.css"', reset($preprocessedContent));

        /** @var $mirror ResourceMirror */
        $mirror = $container->get('orbt_resource_mirror.mirror');
        $this->assertTrue($mirror->exists(new GenericResource('3/images/logos/anu.ico')));
    }

    /**
     * The profile asset preprocessor caches and rewrites secure backend asset URLs.
     *
     * @depends testPreprocess
     */
    public function testPreprocessSecure()
    {
        $container = $this->createClient()->getContainer();
        /** @var $preprocessor CacheProfileAssetPreprocessor */
        $preprocessor = $container->get('anu_style_proxy.cache_profile_asset_preprocessor');

        $profile = new Profile(array(), array(
            'sitevars' => array('https' => true),
            'banner' => 'href="https://styles.anu.edu.au/_anu/3/images/logos/anu.ico"',
        ));
        $preprocessor->preprocess($profile);
        $preprocessedContent = $profile->get('banner');
        $this->assertContains('href="https://localhost/3/images/logos/anu.ico"', $preprocessedContent);
    }
}
