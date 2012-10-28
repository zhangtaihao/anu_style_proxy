<?php

namespace ANU\Bundle\StyleProxyBundle\Tests\Proxy\Profile\Fixtures;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfileCreateEvent;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfileEvents;

class TestProfileListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(ProfileEvents::CREATE => 'onProfileCreate');
    }

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function onProfileCreate(ProfileCreateEvent $event)
    {
        $event->getProfile()->set($this->id, 'onProfileCreate');
    }
}
