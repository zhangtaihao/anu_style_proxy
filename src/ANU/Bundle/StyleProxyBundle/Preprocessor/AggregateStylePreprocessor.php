<?php

namespace ANU\Bundle\StyleProxyBundle\Preprocessor;

use Orbt\StyleMirror\Css\Aggregator;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Profile;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Preprocessor;

/**
 * Preprocessor to aggregate styles.
 */
class AggregateStylePreprocessor implements Preprocessor
{
    /**
     * CSS aggregator.
     * @var Aggregator
     */
    protected $aggregator;

    /**
     * Sets an aggregator to use.
     */
    public function setAggregator(Aggregator $aggregator)
    {
        $this->aggregator = $aggregator;
    }

    /**
     * Aggregates style resources on the profile.
     */
    public function preprocess(Profile $profile)
    {
        // TODO
    }
}
