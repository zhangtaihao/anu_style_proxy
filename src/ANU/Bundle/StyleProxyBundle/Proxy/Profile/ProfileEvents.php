<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy\Profile;

/**
 * Contains events dispatched by the profile handler.
 */
final class ProfileEvents
{
    /**
     * The CREATE event occurs when the profile handler creates the profile from data (e.g. retrieved from backend).
     */
    const CREATE = 'profile.create';
}
