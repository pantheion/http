<?php

namespace Pantheion\Http\Bag;

class DataBag implements \IteratorAggregate, \Countable, \ArrayAccess
{
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function all()
    {
        return $this->data;
    }

    public function keys()
    {
        return array_keys($this->data);
    }

    public function reset(array $data)
    {
        $this->data = $data;
    }

    public function add($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    public function get($key)
    {
        if(!$this->has($key)) return 0; //error

        return $this->data[$key];
    }

    public function set($key, $value)
    {
        if(!$this->has($key)) return 0; //error

        $this->data[$key] = $value;
        return $this;
    }

    public function remove($key)
    {
        if(!$this->has($key)) return 0; //error

        unset($this->data[$key]);
        return $this;
    }

    public function empty()
    {
        return count($this->data) > 0 ? false : true;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    public function count()
    {
        return count($this->data);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
}