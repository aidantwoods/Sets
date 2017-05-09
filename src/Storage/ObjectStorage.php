<?php
declare(strict_types=1);

namespace Aidantwoods\Sets\Storage;

use Aidantwoods\Sets\Storage;

use SplObjectStorage;
use Iterator;

class ObjectStorage implements Storage
{
    protected $SplObjectStorage;

    public function __construct()
    {
        $this->SplObjectStorage = new SplObjectStorage;
    }

    public function union(Iterator $Storage) : void
    {
        foreach ($Storage as $object)
        {
            $this->add($object);
        }
    }

    public function add($object) : void
    {
        $this->SplObjectStorage->attach($object);
    }

    public function contains($object) : bool
    {
        return $this->SplObjectStorage->contains($object);
    }

    public function count() : int
    {
        return $this->SplObjectStorage->count();
    }

    public function remove($object) : void
    {
        $this->SplObjectStorage->detach($object);
    }

    public function offsetExists($object) : bool
    {
        return $this->SplObjectStorage->offsetExists($object);
    }

    public function offsetGet($object)
    {
        return $this->SplObjectStorage->offsetGet($object);
    }

    public function offsetUnset($object, $dumpedData = null) : void
    {
        $this->SplObjectStorage->offsetUnset($object);
    }

    public function offsetSet($object, $dumpedData = null) : void
    {
        $object = $object ?? $dumpedData;

        $this->add($object);
    }

    public function minus(Iterator $Storage) : void
    {
        foreach ($Storage as $object)
        {
            $this->remove($object);
        }
    }

    public function intersection(Iterator $Storage) : void
    {
        $Store = new SplObjectStorage;

        foreach ($Storage as $object)
        {
            $Store->attach($object);
        }

        $this->SplObjectStorage->removeAllExcept($Store);
    }

    public function current()
    {
        return $this->SplObjectStorage->current();
    }

    public function key() : int
    {
        return $this->SplObjectStorage->key();
    }

    public function next() : void
    {
        $this->SplObjectStorage->next();
    }

    public function rewind() : void
    {
        $this->SplObjectStorage->rewind();
    }

    public function valid() : bool
    {
        return $this->SplObjectStorage->valid();
    }
}
