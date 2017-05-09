<?php
declare(strict_types=1);

namespace Aidantwoods\Sets;

use Countable;
use Iterator;

interface SetOperations extends Countable, Iterator
{
    public const ARRAY_FILTER_USE_DATA = 0b01;
    public const ARRAY_FILTER_USE_BOTH = 0b10;

    public function map(callback $callback) : Set;

    public function reduce(callback $callback, $initial = null);

    public function filter(callback $callback, int $flag = 0) : Set;
}
