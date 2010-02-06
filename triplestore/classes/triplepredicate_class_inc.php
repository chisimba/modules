<?php

class triplepredicate extends object implements Iterator
{
    protected $objects;

    public function init()
    {
        $this->objects = array();
    }

    public function __toString()
    {
        return implode(' ', $this->objects);
    }

    public function addObject($object)
    {
        $this->objects[] = $object;
    }

    public function current()
    {
        return current($this->objects);
    }

    public function getObject($index)
    {
        return $this->objects[$index];
    }

    public function key()
    {
        return key($this->objects);
    }

    public function next()
    {
        return next($this->objects);
    }

    public function rewind()
    {
        reset($this->objects);
    }

    public function setObjects($objects)
    {
        $this->objects = $objects;
    }

    public function toArray()
    {
        return $this->objects;
    }

    public function valid()
    {
        return $this->current() !== false;
    }
}
