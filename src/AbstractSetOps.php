<?php
declare(strict_types=1);

namespace Aidantwoods\Sets;

abstract class AbstractSetOps implements SetOperations
{
    protected $Storage;

    public function map(callback $callback) : Set
    {
        $Storage = clone($this);
        $Storage->removeAll();

        foreach ($this as $object)
        {
            $Storage->add($callback($object));
        }

        return $Storage;
    }

    public function reduce(callback $callback, $initial = null)
    {
        $carry = $initial;

        foreach ($this as $object)
        {
            $carry = $callback($carry, $object);
        }

        return $carry;
    }

    public function filter(callback $callback, int $flag = 0) : Set
    {
        $Storage = clone($this);
        $Storage->removeAll();

        foreach ($this as $object)
        {
            $data = $this->offsetGet($object);

            if ((bool) $flag & self::ARRAY_FILTER_USE_DATA)
            {
                $attach = $callback($data);
            }
            elseif ((bool) $flag & self::ARRAY_FILTER_USE_BOTH)
            {
                $attach = $callback($object, $data);
            }
            else
            {
                $attach = $callback($object);
            }

            if ((bool) $attach)
            {
                $Storage->attach($callback($object), $data);
            }
        }

        return $Storage;
    }

    public function __clone()
    {
        $this->SplObjectStorage = clone($this->SplObjectStorage);
    }
}
