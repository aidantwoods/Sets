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

    public function addAll(self $storage) : void;

    public function add($object) : void;

    public function getInfo();

    /**
     * Note, $discardedKey MUST be discarded, there is not sense of ordering in
     * a Set.
     * Instead, an implementation MUST use $object as both a key and a value.
     */
    public function offsetSet($discardedKey, $object) : void;

    public function getSetType() : string;

    public function isScalar() : bool;

    /**
     * MUST return an an instance of the same implementation
     *
     * @return static
     */
    public function subset(callback $suchThat) : self;

    public static function isSetsOfSameType(self $Set1, self $Set2) : bool;
}
