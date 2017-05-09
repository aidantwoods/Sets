<?php
declare(strict_types=1);

namespace Aidantwoods\Sets\Storage;

use SplObjectStorage;

class ObjectStorage extends Storage
{
    protected $SplObjectStorage;

    public function __construct()
    {
        $this->SplObjectStorage = new SplObjectStorage;
    }

    public function union(Storage $Storage) : void
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

    public function remove($object)
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

    public function offsetSet($object)
    {
        $this->SplObjectStorage->offsetSet($object);
    }

    public function minus(self $Storage)
    {
        foreach ($Storage as $object)
        {
            $this->remove($object);
        }
    }

    public function intersection(self $Storage)
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
