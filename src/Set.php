<?php
declare(strict_types=1);

namespace Aidantwoods\Sets;

use ArrayAccess;

interface Set extends SetOperations, ArrayAccess
{
    /**
     * A Set MUST set and fix its containable type on construction.
     */
    public function __construct();

    public function union(self $Storage) : void;

    public function add($value) : void;

    public function contains($value) : bool;

    public function count() : int;

    public function remove($value) : void;

    public function offsetExists($value) : bool;

    public function offsetGet($value);

    public function offsetUnset($value, $dumpedData = null) : void;

    public function offsetSet($value, $dumpedData = null) : void;

    public function minus(self $Storage) : void;

    public function intersection(self $Storage) : void;

    public function current();

    public function key() : int;

    public function next() : void;

    public function rewind() : void;

    public function valid() : bool;

    public function getSetType() : string;

    public function isScalar() : bool;

    public static function isSetsOfSameType(self $Set1, self $Set2) : bool;
}
