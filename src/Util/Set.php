<?php

namespace Canvas\Util;

use IteratorAggregate;
use ArrayIterator;

class Set implements IteratorAggregate
{
    private $elements;

    public function __construct($members = [])
    {
        $this->elements = [];
        foreach ($members as $item) {
            $this->elements[$item] = true;
        }
    }

    public function includes($element)
    {
        return isset($this->elements[$element]);
    }

    public function add($element)
    {
        $this->elements[$element] = true;
    }

    public function discard($element)
    {
        unset($this->elements[$element]);
    }

    public function toArray()
    {
        return array_keys($this->elements);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }
}
