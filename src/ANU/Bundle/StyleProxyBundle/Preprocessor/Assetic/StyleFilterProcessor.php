<?php

namespace ANU\Bundle\StyleProxyBundle\Preprocessor\Assetic;

use Assetic\FilterManager;
use Assetic\Asset\StringAsset;

/**
 * Utility service to use Assetic filters.
 */
class StyleFilterProcessor
{
    protected $manager;
    protected $filters;

    public function __construct(FilterManager $manager, array $filters = array())
    {
        $this->manager = $manager;
        $this->filters = $filters;
    }

    /**
     * Processes content.
     *
     * @param string $content
     * @return string
     */
    public function process($content)
    {
        $asset = new StringAsset($content);
        foreach ($this->filters as $filter) {
            $asset->ensureFilter($this->manager->get($filter));
        }
        return $asset->dump();
    }
}
