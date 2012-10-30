<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy\Profile\Fixtures;

use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfilePreprocess as Preprocess;

/**
 * Basic fixture to expose the preprocessor ordering method.
 */
class ProfilePreprocess extends Preprocess
{
    public function getPreprocessors(array $preprocessorInfo)
    {
        return parent::getPreprocessors($preprocessorInfo);
    }
}
