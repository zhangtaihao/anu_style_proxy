<?php

namespace ANU\Bundle\StyleProxyBundle\Preprocessor;

use Orbt\StyleMirror\Css\Aggregator;
use Orbt\ResourceMirror\ResourceMirror;
use Orbt\ResourceMirror\Resource\Resource;
use Orbt\StyleMirror\Resource\CssResource;
use Orbt\ResourceMirror\Resource\Collection;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Profile;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Preprocessor;

/**
 * Preprocessor to aggregate styles.
 */
class AggregateStylePreprocessor implements Preprocessor
{
    /**
     * Resource mirror.
     * @var ResourceMirror
     */
    protected $mirror;

    /**
     * CSS aggregator.
     * @var Aggregator
     */
    protected $aggregator;

    /**
     * @var string
     */
    protected $backendStyleBase;

    /**
     * @var string
     */
    protected $styleBase;

    /**
     * Creates an style aggregate preprocessor.
     */
    public function __construct(ResourceMirror $mirror, Aggregator $aggregator, $backendStyleBase, $styleBase)
    {
        $this->mirror = $mirror;
        $this->aggregator = $aggregator;
        $this->backendStyleBase = $backendStyleBase;
        $this->styleBase = $styleBase;
    }

    /**
     * Aggregates style resources on the profile.
     */
    public function preprocess(Profile $profile)
    {
        $cssArray = $profile->get('css_arr');
        if (is_array($cssArray)) {
            $backendBaseUrl = $profile->createBaseUrl($this->backendStyleBase)->getUrl();
            $baseUrl = $profile->createBaseUrl($this->styleBase)->getUrl();

            // Aggregate.
            $collection = $this->getCollectionFromLinks($cssArray, $backendBaseUrl);
            $aggregateResource = $this->aggregator->aggregate($collection);

            // TODO Filter with Assetic.

            // Save to profile.
            $resource = $this->mirror->materialize($aggregateResource);
            $link = $this->getLinkFromResource($resource, $baseUrl);
            $profile->set('css_arr', array($link));
        }
    }

    /**
     * Converts a list of link tags into a collection of CssResource objects.
     * TODO Generalize link tag scanning and extraction for full documents.
     */
    private function getCollectionFromLinks(array $tags, $backendBaseUrl)
    {
        $attributePattern = '([^=]+)="([^"]*)"';
        $tagPattern = '<link((?:\s+'.$attributePattern.')*)\s*/>';

        $collection = new Collection();
        foreach ($tags as $tag) {
            // Match link tag.
            if (preg_match_all("`$tagPattern`i", $tag, $tagMatches)) {
                foreach ($tagMatches[1] as $tagAttributes) {
                    // Match attributes and build link.
                    preg_match_all("`$attributePattern`i", $tagAttributes, $attributeMatches, PREG_SET_ORDER);
                    $link = array();
                    foreach ($attributeMatches as $attributeMatch) {
                        $link[$attributeMatch[1]] = $attributeMatch[2];
                    }
                    // Create and collect resource.
                    if (isset($link['href'])) {
                        $link += array('media' => 'all');
                        try {
                            $resource = CssResource::fromUrl($link['href'], $backendBaseUrl, $link['media']);
                            $collection->add($resource);
                        }
                        catch (\InvalidArgumentException $e) {}
                    }
                }
            }
        }

        return $collection;
    }

    /**
     * Converts a resource into link tag.
     */
    private function getLinkFromResource(Resource $resource, $baseUrl, $mediaType = 'all') {
        $href = $baseUrl.'/'.$resource->getPath();
        return '<link href="'.$href.'" rel="stylesheet" type="text/css" media="'.$mediaType.'" />';
    }
}
