<?php

namespace ANU\Bundle\StyleProxyBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfileCreateEvent;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfileEvents;
use ANU\Bundle\StyleProxyBundle\Proxy\BaseUrl;

class CacheProfileListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(ProfileEvents::CREATE => 'onProfileCreate');
    }

    /**
     * @var BaseUrl
     */
    protected $baseUrl;

    /**
     * @var BaseUrl
     */
    protected $backendBaseUrl;

    public function __construct(BaseUrl $baseUrl, BaseUrl $backendBaseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->backendBaseUrl = $backendBaseUrl;
    }

    /**
     * Localizes all profiles resources.
     */
    public function onProfileCreate(ProfileCreateEvent $profile)
    {
        // TODO
    }
}
