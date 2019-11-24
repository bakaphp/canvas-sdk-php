<?php

namespace Canvas;

use Canvas\Util\Set;

class CanvasObject
{
    protected $_opts;
    protected $_originalValues;
    protected $_values;
    protected $_unsavedValues;
    protected $_transientValues;
    protected $_retrieveOptions;
    protected $_lastResponse;

    /**
     * Consstructor.
     *
     * @param string|mixed $id
     * @param mixed $opts
     */
    public function __construct($id = null, $opts = null)
    {
        list($id, $this->_retrieveOptions) = Util\Util::normalizeId($id);
        $this->_opts = Util\RequestOptions::parse($opts);
        $this->_originalValues = [];
        $this->_values = [];
        $this->_unsavedValues = new Set();
        $this->_transientValues = new Set();
        if ($id !== null) {
            $this->_values['id'] = $id;
        }
    }

    /**
     * This unfortunately needs to be public to be used in Util\Util.
     *
     * @param array $values
     * @param null|string|array|Util\RequestOptions $opts
     *
     * @return static The object constructed from the given values.
     */
    public static function constructFrom($values, $opts = null)
    {
        $obj = new static(isset($values['id']) ? $values['id'] : null);
        $obj->refreshFrom($values, $opts);
        return $obj;
    }

    /**
     * Refreshes this object using the provided values.
     *
     * @param array $values
     * @param null|string|array|Util\RequestOptions $opts
     * @param boolean $partial Defaults to false.
     *
     * @return void
     */
    public function refreshFrom($values, $opts, $partial = false): void
    {
        $this->_opts = Util\RequestOptions::parse($opts);
        $this->_values = $values;
    }

    /**
     * Get the dinaymic properties for this object.
     *
     * @param string $k
     * @return mixed
     */
    public function __get($k)
    {
        // function should return a reference, using $nullval to return a reference to null
        $nullval = null;
        if (!empty($this->_values) && array_key_exists($k, $this->_values)) {
            return $this->_values[$k];
        } else {
            $class = get_class($this);
            Canvas::getLogger()->error("Kanvas Notice: Undefined property of $class instance: $k");
            return $nullval;
        }
    }

    /**
     *  Standard accessor magic methods.
     *
     * @param string $k
     * @param mixed $v
     * @return void
     */
    public function __set($k, $v)
    {
        if (static::getPermanentAttributes()->includes($k)) {
            throw new Exception\InvalidArgumentException(
                "Cannot set $k on this object. HINT: you can't set: " .
                join(', ', static::getPermanentAttributes()->toArray())
            );
        }
        if ($v === '') {
            throw new Exception\InvalidArgumentException(
                'You cannot set \'' . $k . '\'to an empty string. '
                . 'We interpret empty strings as NULL in requests. '
                . 'You may set obj->' . $k . ' = NULL to delete the property'
            );
        }
        $this->_values[$k] = Util\Util::convertToSimpleObject($v, $this->_opts);
        $this->dirtyValue($this->_values[$k]);
        $this->_unsavedValues->add($k);
    }

    /**
     * Attributes that should not be sent to the API because they're not updatable (e.g. ID).
     *
     * @return Set
     */
    public static function getPermanentAttributes(): Set
    {
        static $permanentAttributes = null;
        if ($permanentAttributes === null) {
            $permanentAttributes = new Set([
                'id',
            ]);
        }
        return $permanentAttributes;
    }

    /**
     * Does it exist?
     *
     * @param string $k
     * @return boolean
     */
    public function __isset($k): bool
    {
        return isset($this->_values[$k]);
    }

    /**
     * Remove elment
     *
     * @param [type] $k
     */
    public function __unset($k): void
    {
        unset($this->_values[$k]);
        $this->_transientValues->add($k);
        $this->_unsavedValues->discard($k);
    }
}
