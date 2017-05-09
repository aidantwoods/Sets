<?php
declare(strict_types=1);

namespace Aidantwoods\Sets;

use ArrayAccess;
use Countable;
use Iterator;

interface Storage extends Countable, Iterator, ArrayAccess
{
    public function union(Iterator $Storage) : void;

    public function add($value) : void;

    public function contains($value) : bool;

    public function count() : int;

    public function remove($value) : void;

    public function offsetExists($value) : bool;

    public function offsetGet($value);

    public function offsetUnset($value, $dumpedData = null) : void;

    public function offsetSet($value, $dumpedData = null) : void;

    public function minus(Iterator $Storage) : void;

    public function intersection(Iterator $Storage) : void;

    public function current();

    public function key() : int;

    public function next() : void;

    public function rewind() : void;

    public function valid() : bool;
}
