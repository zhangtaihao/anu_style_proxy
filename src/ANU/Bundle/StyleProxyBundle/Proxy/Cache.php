<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy;

use Doctrine\Common\Cache\Cache as DoctrineCache;

/**
 * Application data cache interface, delegating to the contained service.
 */
class Cache implements DoctrineCache
{
    protected $cache;

    public function __construct(DoctrineCache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($id)
    {
        return $this->cache->fetch($id);
    }

    /**
     * {@inheritdoc}
     */
    public function contains($id)
    {
        return $this->cache->contains($id);
    }

    /**
     * {@inheritdoc}
     */
    public function save($id, $data, $lifeTime = 0)
    {
        return $this->cache->save($id, $data, $lifeTime);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        return $this->cache->delete($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getStats()
    {
        return $this->cache->getStats();
    }

}
