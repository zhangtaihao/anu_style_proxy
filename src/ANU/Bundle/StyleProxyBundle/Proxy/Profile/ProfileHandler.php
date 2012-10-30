<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy\Profile;

use Doctrine\Common\Cache\Cache;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use ANU\Bundle\StyleProxyBundle\Exception\ProfileNotFoundException;
use ANU\Bundle\StyleProxyBundle\Exception\ProfileNotCachedException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Handler for creating and processing profiles and retrieving them from cache.
 */
class ProfileHandler extends ContainerAware
{
    /**
     * Application cache service.
     * @var Cache
     */
    protected $cache;

    /**
     * Event dispatcher
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Cache life time.
     * @var int
     */
    protected $cacheLifetime = 0;

    private $instances = array();

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Sets the cache lifetime.
     *
     * @param int $lifetime
     *   Number of seconds to cache a profile.
     */
    public function setCacheLifetime($lifetime)
    {
        $this->cacheLifetime = $lifetime;
    }

    /**
     * Sets the event dispatcher.
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Gets a profile for a query.
     *
     * @param array $query
     *   Query parameters for the profile.
     * @return Profile
     *   Profile matching query.
     *
     * @throws ProfileNotFoundException
     *   If the query does not match a profile object.
     */
    public function getProfile(array $query)
    {
        return $this->lookupCache($query);
    }

    /**
     * Creates a profile from data.
     *
     * @param array $query
     *   Profile query.
     * @param mixed $data
     *   JSON string or array data for profile.
     * @param bool $cache
     *   Whether to cache the profile for retrieval via self::getProfile().
     * @return Profile
     *   Created profile object.
     *
     * @throws \InvalidArgumentException
     *   If the given data cannot be used to create the profile.
     */
    public function createProfile(array $query, $data, $cache = true)
    {
        $request = null;
        if ($this->container->hasScope('request')) {
            $request = $this->container->get('request');
        }
        $profile = new Profile($query, $data, $request);

        // Dispatch profile create event to process profile.
        if ($this->dispatcher) {
            $this->dispatcher->dispatch(ProfileEvents::CREATE, new ProfileCreateEvent($profile));
        }

        if ($cache) {
            $this->storeCache($profile);
        }
        return $profile;
    }

    /**
     * Looks up a cached entry.
     */
    protected function lookupCache(array $query)
    {
        $id = $this->getNormalizedCacheId($query);
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }
        elseif ($this->cache->contains($id)) {
            $json = $this->cache->fetch($id);
            return new Profile($query, $json);
        }
        throw new ProfileNotCachedException('Profile cache does not exist or has expired.');
    }

    /**
     * Stores a profile to cache.
     */
    protected function storeCache(Profile $profile)
    {
        $query = $profile->getQuery();
        $id = $this->getNormalizedCacheId($query);
        $this->cache->save($id, $profile->getSerializedData(), $this->cacheLifetime);
        $this->instances[$id] = $profile;
    }

    /**
     * Gets a normalized caching identifier for a query.
     */
    protected function getNormalizedCacheId(array $query)
    {
        // Remove part.
        if (isset($query['part'])) {
            unset($query['part']);
        }
        // Sort by key.
        ksort($query);
        // Compute hash to finish.
        return 'profile:'.md5(serialize($query));
    }
}
