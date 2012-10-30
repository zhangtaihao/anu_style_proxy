<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Preprocessor;

use ANU\Bundle\StyleProxyBundle\Preprocessor\CacheProfileAssetPreprocessor;
use Orbt\StyleMirror\Css\Aggregator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use ANU\Bundle\StyleProxyBundle\Preprocessor\AggregateStylePreprocessor;
use Orbt\ResourceMirror\Resource\GenericResource;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Profile;
use Orbt\ResourceMirror\ResourceMirror;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AggregateStylePreprocessorTest extends WebTestCase
{
    /**
     * A style aggregate preprocessor can be instantiated.
     */
    public function testCreate()
    {
        $dispatcher = new EventDispatcher();
        $backendStyleBase = 'http://example.com';
        $mirror = new ResourceMirror($dispatcher, $backendStyleBase, sys_get_temp_dir());
        $aggregator = new Aggregator($mirror);
        $preprocessor = new AggregateStylePreprocessor($mirror, $aggregator, $backendStyleBase, 'http://localhost');
        $this->assertInstanceOf('ANU\Bundle\StyleProxyBundle\Preprocessor\AggregateStylePreprocessor', $preprocessor);
    }

    /**
     * A style aggregate preprocessor aggregates style sheets in a profile.
     *
     * @depends testCreate
     */
    public function testPreprocess()
    {
        $dispatcher = new EventDispatcher();
        $backendStyleBase = 'http://styles.anu.edu.au/_anu';
        $mirror = new ResourceMirror($dispatcher, $backendStyleBase, sys_get_temp_dir());
        $aggregator = new Aggregator($mirror);
        $preprocessor = new AggregateStylePreprocessor($mirror, $aggregator, $backendStyleBase, 'http://localhost/base');

        $data = json_decode(file_get_contents(__DIR__.'/../Proxy/Profile/demoprofile.json'), true);
        $profile = new Profile(array(), $data);
        $preprocessor->preprocess($profile);
        $this->assertCount(1, $profile->get('css_arr'));
        $this->assertContains('href="http://localhost/base', $meta = $profile->get('meta'));
        preg_match('`http://localhost/base/([^"]+)`', $meta, $match);
        $this->assertFileExists($file = sys_get_temp_dir().'/'.$match[1]);
        $this->assertGreaterThan(0, filesize($file));
    }
}
