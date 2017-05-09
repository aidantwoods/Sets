<?php
declare(strict_types=1);

namespace Aidantwoods\Sets\Storage;

use Aidantwoods\Sets\Storage;

use OutOfBoundsException;
use Iterator;

class ValueStorage implements Storage
{
    protected $Storage;

    public function __construct()
    {
        $this->Storage = [];
    }

    public function union(Iterator $Storage) : void
    {
        foreach ($Storage as $value)
        {
            $this->add($value);
        }
    }

    public function add($value) : void
    {
        if ( ! $this->contains($value))
        {
            $this->Storage[] = $value;
        }
    }

    public function contains($value) : bool
    {
        return in_array($value, $this->Storage, true);
    }

    public function count() : int
    {
        return count($this->Storage);
    }

    public function remove($value) : void
    {
        if (($k = array_search($value, $this->Storage, true)) !== false)
        {
            unset($this->Storage[$k]);
        }
    }

    public function offsetExists($value) : bool
    {
        return $this->contains($value);
    }

    public function offsetGet($value)
    {
        if ($this->contains($value))
        {
            return $value;
        }
        else
        {
            throw new OutOfBoundsException(
                "No value of value ".print_r($value, true)
            );
        }
    }

    public function offsetUnset($value, $dumpedData = null) : void
    {
        $this->remove($value);
    }

    public function offsetSet($value, $dumpedData = null) : void
    {
        $this->add($value);
    }

    public function minus(Iterator $Storage) : void
    {
        foreach ($Storage as $value)
        {
            $this->remove($value);
        }
    }

    public function intersection(Iterator $Storage) : void
    {
        list($Store1, $Store2) = (
            count($this) < count($Storage) ?
                [$this, $Storage] : [$Storage, $this]
        );

        list($Store1, $Store2) = [clone($Store1), clone($Store2)];

        foreach ($Store1 as $value)
        {
            if ( ! $Store2->contains($value))
            {
                $Store1->remove($value);
            }
        }

        $this->Storage = [];

        $this->union($Store1);
    }

    public function current()
    {
        return current($this->Storage);
    }

    public function key() : int
    {
        return $this->current();
    }

    public function next() : void
    {
        next($this->Storage);
    }

    public function rewind() : void
    {
        reset($this->Storage);
    }

    public function valid() : bool
    {
        return $this->current() !== false;
    }
}
