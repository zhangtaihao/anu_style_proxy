<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy\Profile;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event dispatched by the profile handler.
 */
class ProfileEvent extends Event
{
    /**
     * @var Profile
     */
    protected $profile;

    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
