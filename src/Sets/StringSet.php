<?php
declare(strict_types=1);

namespace Aidantwoods\Sets\Sets;

class StringSet extends AbstractSet implements Set
{
    public function __construct()
    {
        $this->setSetType('string');
    }
}
