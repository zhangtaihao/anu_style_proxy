<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy\Profile;

/**
 * A style profile for a site given a site ID.
 */
class Profile
{
    protected $data;

    public function __construct($jsonData)
    {
        if (is_string($jsonData)) {
            $this->data = json_decode($jsonData);
            if ($this->data === null) {
                throw new \InvalidArgumentException('Unreadable JSON string.');
            }
        }
        elseif (is_array($jsonData)) {
            $this->data = $jsonData;
        }
        else {
            throw new \InvalidArgumentException('Invalid input JSON data.');
        }
    }

    /**
     * Returns the full profile data.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns the serialized data.
     */
    public function getSerializedData()
    {
        return json_encode($this->data);
    }
}
