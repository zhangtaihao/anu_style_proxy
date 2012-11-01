<?php

namespace ANU\Bundle\StyleProxyBundle\Preprocessor;

use Orbt\ResourceMirror\ResourceMirror;
use Orbt\ResourceMirror\Exception\MaterializeException;
use Orbt\ResourceMirror\Resource\GenericResource;
use Symfony\Component\HttpFoundation\Request;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Preprocessor;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Profile;
use Symfony\Component\DependencyInjection\ContainerInterface;
use ANU\Bundle\StyleProxyBundle\Proxy\BaseUrl;

/**
 * Cache assets when preprocessing a profile.
 */
class CacheProfileAssetPreprocessor implements Preprocessor
{
    /**
     * @var ResourceMirror
     */
    protected $mirror;

    /**
     * @var string
     */
    protected $backendStyleBase;

    /**
     * @var string
     */
    protected $styleBase;

    /**
     * Creates an asset cache preprocessor.
     */
    public function __construct(ResourceMirror $mirror, $backendStyleBase, $styleBase)
    {
        $this->mirror = $mirror;
        $this->backendStyleBase = $backendStyleBase;
        $this->styleBase = $styleBase;
    }

    /**
     * Caches backend assets for a profile.
     */
    public function preprocess(Profile $profile)
    {
        // Derive URL from parameters.
        $backendBaseUrl = $profile->createBaseUrl($this->backendStyleBase)->getUrl();
        $baseUrl = $profile->createBaseUrl($this->styleBase)->getUrl();

        $this->preprocessAssetUrls($profile, $backendBaseUrl, $baseUrl);
    }

    /**
     * Localizes all profiles resources.
     */
    private function preprocessAssetUrls(Profile $profile, $backendBaseUrl, $baseUrl)
    {
        // Process parts containing HTML but excluding derived attributes, e.g. 'meta'.
        // TODO Need a better way of handling derived attributes. Perhaps explicit setter methods on the profile?
        $resourcePaths = array();
        foreach (array('banner', 'footer', 'meta_arr', 'icon_arr', 'css_arr', 'css_ie_arr') as $attribute) {
            $content = $profile->get($attribute);
            if (is_string($content)) {
                $content = $this->cacheAssets($content, $backendBaseUrl, $baseUrl, $paths);
                $resourcePaths = array_merge($resourcePaths, $paths);
            }
            elseif (is_array($content)) {
                foreach ($content as &$contentItem) {
                    $contentItem = $this->cacheAssets($contentItem, $backendBaseUrl, $baseUrl, $paths);
                    $resourcePaths = array_merge($resourcePaths, $paths);
                }
                unset($contentItem);
            }
            $profile->set($attribute, $content);
        }

        // Materialize accumulated resources.
        foreach ($resourcePaths as $path) {
            try {
                $this->mirror->materialize(new GenericResource($path));
            }
            catch (MaterializeException $e) {}
        }
    }

    /**
     * Scans resources in content and materializes them.
     *
     * Only style sheets and images are cached.
     */
    private function cacheAssets($content, $backendBaseUrl, $baseUrl, &$paths)
    {
        $delimiter = '`';
        // Match src/href attribute.
        $pattern = '((?:src|href)=["\'])';
        // Match base URL.
        $pattern .= '('.preg_quote($backendBaseUrl.'/', $delimiter).')';
        // Match everything up to extension.
        $pattern .= '(';
        $pattern .= '(?!\.[a-z]+["\'])[^"\']*';
        // Match styles sheets and images.
        $pattern .= '\.(?:css|png|gif|jpe?g|ico)';
        $pattern .= ')';
        // Match closing quote.
        $pattern .= '(["\'])';

        // Rewrite and accumulate paths.
        $paths = array();
        $callback = function ($match) use ($baseUrl, &$paths) {
            $paths[] = $match[3];
            return $match[1].$baseUrl.'/'.$match[3].$match[4];
        };
        $content = preg_replace_callback($delimiter.$pattern.$delimiter.'i', $callback, $content);

        return $content;
    }
}
























