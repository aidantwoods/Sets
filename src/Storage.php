<?php
declare(strict_types=1);

namespace Aidantwoods\Sets;

use ArrayAccess;
use Countable;
use Iterator;

interface Storage extends Countable, Iterator, ArrayAccess
{
    public function union(self $Storage) : void;

    public function add($value) : void;

    public function contains($value) : bool;

    public function count() : int;

    public function remove($value) : void;

    public function offsetExists($value) : bool;

    public function offsetGet($value);

    public function offsetSet($value) : void;

    public function minus(self $Storage) : void;

    public function intersection(self $Storage) : void;

    public function current();

    public function key() : int;

    public function next() : void;

    public function rewind() : void;

    public function valid() : bool;
}
