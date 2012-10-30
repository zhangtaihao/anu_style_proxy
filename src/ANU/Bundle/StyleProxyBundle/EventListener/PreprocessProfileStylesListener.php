<?php

namespace ANU\Bundle\StyleProxyBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfilePreprocess;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfileCreateEvent;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfileEvents;

class PreprocessProfileStylesListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(ProfileEvents::CREATE => 'onProfileCreate');
    }

    /**
     * @var ProfilePreprocess
     */
    protected $preprocess;

    /**
     * Constructs a profile listener for preprocessing.
     */
    public function __construct(ProfilePreprocess $preprocess)
    {
        $this->preprocess = $preprocess;
    }

    /**
     * Preprocesses a profile on creation.
     */
    public function onProfileCreate(ProfileCreateEvent $e)
    {
        $this->preprocess->preprocess($e->getProfile());
    }
}
