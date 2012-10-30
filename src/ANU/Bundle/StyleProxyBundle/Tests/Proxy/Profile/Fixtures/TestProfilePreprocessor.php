<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy\Profile\Fixtures;

use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Preprocessor;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Profile;

class TestProfilePreprocessor implements Preprocessor
{
    public function preprocess(Profile $profile)
    {
        $profile->set('test_profile_preprocessor', 'preprocess');
    }
}
