<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy\Profile;

/**
 * A style profile for a site given a site ID.
 */
class Profile
{
    protected $query;
    protected $data;

    public function __construct(array $query, $jsonData)
    {
        $this->query = $query;

        if (is_string($jsonData)) {
            $this->data = json_decode($jsonData, true);
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
     * Returns the query used to create this profile.
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Returns an attribute of the profile data.
     */
    public function get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * Returns a site variable from the 'sitevars' attribute.
     *
     * If no 'sitevars' attribute exists, null is returned.
     */
    public function getSiteVariable($name)
    {
        return isset($this->data['sitevars'][$name]) ? $this->data['sitevars'][$name] : null;
    }

    /**
     * Updates an attribute of the profile.
     *
     * Data updated here may reflect in other parts of the profile. For example, updating 'meta_arr' will cause the
     * 'meta' attribute to be updated in order to maintain consistency. Note that manually updating derivative
     * attributes such as 'meta' may cause the profile data to no longer be correct.
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;
        $this->updateData($name);
    }

    /**
     * Updates profile data.
     */
    protected function updateData($name)
    {
        $this->updateMeta($name);
    }

    /**
     * Updates the meta attribute.
     */
    protected function updateMeta($name)
    {
        static $attributes = array('meta_arr', 'icon_arr', 'css_arr', 'js_arr', 'css_ie_arr');
        if (in_array($name, $attributes)) {
            $meta = array();
            $data = array_intersect_key($this->data, array_flip($attributes));
            foreach ($data as $value) {
                if (is_array($value)) {
                    $meta = array_merge($meta, $value);
                }
            }
            $this->data['meta'] = implode("\n", $meta);
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
