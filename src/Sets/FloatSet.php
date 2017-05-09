<?php
declare(strict_types=1);

namespace Aidantwoods\Sets\Sets;

use Aidantwoods\Sets\Set;

class FloatSet extends AbstractSet implements Set
{
    public function __construct()
    {
        $this->setSetType('float');
    }
}
