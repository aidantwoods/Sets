<?php
declare(strict_types=1);

namespace Aidantwoods\Sets;

use Countable;
use Iterator;

interface SetOperations extends Countable, Iterator
{
    public function map(callback $callback) : Set;

    public function reduce(callback $callback, $initial = null);

    public function filter(callback $callback) : Set;
}
