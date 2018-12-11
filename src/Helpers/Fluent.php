<?php

namespace DarkGhostHunter\FlowSdk\Helpers;

use ArrayAccess;
use Closure;
use Countable;
use DarkGhostHunter\FlowSdk\Exceptions\Fluent\AttributesOnlyException;
use DarkGhostHunter\FlowSdk\Exceptions\Fluent\AttributesRequiredException;
use JsonSerializable;

class Fluent implements ArrayAccess, JsonSerializable, Countable
{
    use FluentConcerns\IsCountable,
        FluentConcerns\IsArrayAccessible,
        FluentConcerns\IsJsonSerializable;

    /**
     * Attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Attributes hidden
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Attributes that are required when instancing
     *
     * @var array
     */
    protected $required;

    /**
     * If the Fluent only accepts the required attributes
     *
     * @var bool
     */
    protected $restrained = false;

    /**
     * Protected properties to merge on serialization
     *
     * @var array
     */
    protected $merge = [];

    /**
     * Fluent constructor.
     *
     * Allows to construct a Fluent instance using the attributes
     *
     * @param array $attributes
     * @throws \Exception
     */
    public function __construct(array $attributes = [])
    {
        $this->checkRequiredAndRestrained($attributes);

        $this->setAttributes($attributes);
    }

    /**
     * Checks if the attributes comply with the required ones
     *
     * @param array $attributes
     * @throws AttributesRequiredException
     * @throws AttributesOnlyException
     */
    protected function checkRequiredAndRestrained(array $attributes)
    {
        $required = count(
            $this->required !== null
            ? $this->required: []
        );

        // First, check if we're receiving the required attributes by just
        // simply counting the required attributes, and comparing it to
        // the number of attributes that should have been declared.
        if (!!$required
            && $required > $filled = count(array_intersect_key(array_flip($this->required), $attributes))) {
            throw new AttributesRequiredException($this->required, $attributes);
        }

        // Second, if this class is restrained to the required attributes,
        // we will throw an exception if attributes numbers doesn't match
        // the number of the required ones.
        if ($this->restrained && $required !== count($attributes)) {
            throw new AttributesOnlyException($this->required);
        }
    }

    /**
     * Sets an array of attributes into the Fluent object
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $name => $attribute) {
            $this->set($name, $attribute);
        }
    }

    /**
     * Sets an array of attributes into de Fluent object directly
     *
     * @param array $attributes
     */
    public function setRawAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Returns an attribute
     *
     * @param string $attribute
     * @param null $default
     * @return mixed
     */
    public function get($attribute, $default = null)
    {
        // Return the attribute using the getters if set
        if (method_exists($this, $method = 'get' . ucfirst($attribute) . 'Attribute')) {
            return $this->$method();
        }

        // If not, then return the attribute if is set except on null
        if (isset($this->attributes[$attribute])) {
            return $this->attributes[$attribute] instanceof Closure
                ? $this->attributes[$attribute]()
                : $this->attributes[$attribute];
        }

        // Then just return the default, which is null
        return $default instanceof Closure
            ? $default()
            : $default;
    }

    /**
     * Sets an attribute
     *
     * @param string $name
     * @param $attribute
     */
    public function set($name, $attribute)
    {
        // Use the setter if set, and return.
        if (method_exists($this, $method = 'set' . ucfirst($name) . 'Attribute')) {
            $this->$method($attribute);
            return;
        }

        $this->attributes[$name] = $attribute;
    }

    /**
     * Returns all the attributes, or only selected attributes
     *
     * @param array|null $only
     * @return array
     */
    public function getAttributes(...$only)
    {
        $attributes = count($only)
            ? array_intersect_key(array_flip($only), $this->attributes)
            : $this->attributes;

        foreach ($attributes as $key => &$attribute) {
            $attribute = $this->get($key);
        }

        return $attributes;
    }

    /**
     * Returns all the raw attributes, or only the selected attributes
     *
     * @param array $only
     * @return array
     */
    public function getRawAttributes(...$only)
    {
        return count($only)
            ? array_intersect_key($this->attributes, array_flip($only))
            : $this->attributes;
    }

    /**
     * Return a single raw attribute
     *
     * @param string $attribute
     * @return mixed
     */
    public function getRawAttribute($attribute)
    {
        return $this->attributes[$attribute];
    }

    /**
     * Return the protected properties to merge
     *
     * @return array
     */
    public function getMerge()
    {
        return $this->merge;
    }

    /**
     * Return the attribute to hide from serialization
     *
     * @return array
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Transforms the object into an Array
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];

        // Merge the protected properties if these are set to merged
        foreach ($this->merge as $merge) {
            $array[$merge] = $this->$merge;
        }

        // Merge these with the attributes
        $array = array_merge($array, $this->getAttributes());

        // Hide the attributes to be set as hidden before serialization
        foreach ($this->hidden as $hide) {
            unset($array[$hide]);
        }

        // Return the cleaned array
        return $array;
    }

    /**
     * String representation of the class
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->jsonSerialize());
    }

    /**
     * Returns the attribute dynamically
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Sets the attribute dynamically
     *
     * @param $name
     * @param $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     * Check if the attribute exists
     *
     * @param  string $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset an attribute.
     *
     * @param  string $key
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Call a Fluent method constructor for attributes
     *
     * @param $name
     * @param $arguments
     * @return Fluent|$this
     */
    public function __call($name, $arguments)
    {
        $this->set($name, count($arguments) > 0 ? $arguments[0] : true);

        return $this;
    }

    /**
     * Creates a new instance of the class
     *
     * @param array $attributes
     * @return Fluent|$this
     * @throws \Exception
     */
    public static function make(array $attributes)
    {
        return new static($attributes);
    }

    /**
     * Creates a new instance of the class from JSON
     *
     * @param string $json
     * @return Fluent|$this
     * @throws \Exception
     */
    public static function fromJson($json)
    {
        return new static(json_decode($json, true));
    }
}