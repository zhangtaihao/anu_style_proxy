<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy\Profile;

use Doctrine\Common\Cache\Cache;
use ANU\Bundle\StyleProxyBundle\Exception\ProfileNotFoundException;
use ANU\Bundle\StyleProxyBundle\Exception\ProfileNotCachedException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Handler for creating and processing profiles and retrieving them from cache.
 */
class ProfileHandler
{
    /**
     * Application cache service.
     * @var Cache
     */
    protected $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
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
        $profile = new Profile($query, $data);

        // TODO Process profile.

        return $profile;
    }

    /**
     * Looks up a cached entry.
     */
    protected function lookupCache(array $query)
    {
        $id = $this->getNormalizedCacheId($query);
        if ($this->cache->contains($id)) {
            $json = $this->cache->fetch($id);
            return new Profile($query, $json);
        }
        throw new ProfileNotCachedException('Profile cache does not exist or has expired.');
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
