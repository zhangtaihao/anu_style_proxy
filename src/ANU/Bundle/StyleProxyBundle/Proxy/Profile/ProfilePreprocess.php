<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy\Profile;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Main preprocess handler for a profile.
 */
class ProfilePreprocess
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $preprocessors;

    /**
     * Creates a preprocess handler.
     */
    public function __construct(ContainerInterface $container, array $preprocessorInfo)
    {
        $this->container = $container;
        $this->preprocessors = $this->getPreprocessors($preprocessorInfo);
    }

    /**
     * Sorts preprocessor identifier array.
     */
    protected function getPreprocessors(array $preprocessorInfo)
    {
        // First add priority differential to pin the current order.
        $increment = $delta = 0.01 / count($preprocessorInfo);
        foreach ($preprocessorInfo as $id => $preprocessor) {
            $preprocessorInfo += array(
                'priority' => $increment,
            );
            $increment += $delta;
        }
        // Sort.
        uasort($preprocessorInfo, function ($a, $b) {
            return ($a['priority'] < $b['priority']) ? -1 : 1;
        });
        // Return ordered servcice IDs.
        return array_keys($preprocessorInfo);
    }

    /**
     * Preprocesses a profile.
     */
    public function preprocess(Profile $profile)
    {
        foreach ($this->preprocessors as $id) {
            /** @var $preprocessor Preprocessor */
            $preprocessor = $this->container->get($id);
            $preprocessor->preprocess($profile);
        }
    }
}
