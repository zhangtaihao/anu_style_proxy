<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy\Profile;

/**
 * Interface for a preprocessor that optimises a profile, e.g. as it created.
 */
interface Preprocessor
{
    /**
     * Preprocesses a profile. The preprocessor directly performs operations on the profile.
     *
     * @param Profile $profile
     */
    public function preprocess(Profile $profile);
}
