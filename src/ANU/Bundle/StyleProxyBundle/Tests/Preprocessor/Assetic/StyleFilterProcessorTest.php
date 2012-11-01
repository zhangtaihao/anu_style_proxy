<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Preprocessor\Assetic;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use ANU\Bundle\StyleProxyBundle\Preprocessor\Assetic\StyleFilterProcessor;

class StyleFilterProcessorTest extends WebTestCase
{
    /**
     * A style filter processor can be instantiated.
     */
    public function testCreate()
    {
        $manager = $this->createClient()->getContainer()->get('assetic.filter_manager');
        $processor = new StyleFilterProcessor($manager, array('cssmin'));
        $this->assertInstanceOf('ANU\Bundle\StyleProxyBundle\Preprocessor\Assetic\StyleFilterProcessor', $processor);
    }

    /**
     * A style filter processor filters style sheets.
     *
     * @depends testCreate
     */
    public function testProcess()
    {
        $manager = $this->createClient()->getContainer()->get('assetic.filter_manager');
        $processor = new StyleFilterProcessor($manager, array('cssmin'));
        $content = '/* comment */body { font-size: 12px; }';
        $processedContent = $processor->process($content);
        $this->assertNotEmpty($processedContent);
        $this->assertLessThan(strlen($content), strlen($processedContent));
    }
}
