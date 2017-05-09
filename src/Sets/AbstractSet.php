<?php
declare(strict_types=1);

namespace Aidantwoods\Sets\Sets;

use Aidantwoods\Sets\AbstractSetOps;
use Aidantwoods\Sets\Set;
use Aidantwoods\Sets\Storage\ObjectStorage;
use Aidantwoods\Sets\Storage\ValueStorage;

use InvalidArgumentException;
use LogicException;

/**
 * A Set is like Storage, except the type of the contained objects
 * is fixed on construction.
 */
abstract class AbstractSet extends AbstractSetOps implements Set
{
    protected const SCALARS = [
        'array',
        'bool',
        'callable',
        'float',
        'int',
        'string',
    ];

    protected $className;
    protected $isScalar;
    protected $scalarTest;

    protected function setSetType(string $className)
    {
        if ($this->className !== null)
        {
            throw new LogicException('May not redefine Set type');
        }

        $lowerClassName = strtolower($className);

        if (in_array($lowerClassName, self::SCALARS, true))
        {
            if ($lowerClassName === 'bool')
            {
                throw new InvalidArgumentException('Bool Sets not supported');
            }

            $this->isScalar   = true;
            $this->className  = $lowerClassName;
            $this->scalarTest = "is_{$this->className}";

            $this->Storage = new ValueStorage;
        }
        else
        {
            $this->className = $className;

            $this->Storage = new ObjectStorage;
        }
    }

    public function getSetType() : string
    {
        return $this->className;
    }

    public function subset(callback $suchThat) : Set
    {
        return $this->filter($suchThat);
    }

    public function addArray(array $storage) : void
    {
        if ($this->isArrayContainable($storage))
        {
            foreach ($storage as $object)
            {
                $this->add($object);
            }
        }
        else
        {
            throw new InvalidArgumentException(
                $this->containerTypeExceptionMsg(1, __METHOD__)
            );
        }
    }

    public function union(Set $Storage) : void
    {
        if ($this->isSetContainable($Storage))
        {
            $this->Storage->union($Storage);
        }
        else
        {
            throw new InvalidArgumentException(
                $this->containerTypeExceptionMsg(1, __METHOD__)
            );
        }
    }

    public function add($object) : void
    {
        if ( ! $this->isContainable($object))
        {
            throw new InvalidArgumentException(
                $this->typeExceptionMsg(1, __METHOD__, $object)
            );
        }

        $this->Storage->add($object);
    }

    public function contains($object) : bool
    {
        return $this->Storage->contains($object);
    }

    public function count() : int
    {
        return $this->Storage->count();
    }

    public function remove($object) : void
    {
        $this->Storage->remove($object);
    }

    public function offsetExists($object) : bool
    {
        return $this->Storage->offsetExists($object);
    }

    public function offsetGet($object)
    {
        return $this->Storage->offsetGet($object);
    }

    public function offsetUnset($object, $dumpedData = null) : void
    {
        $this->Storage->offsetUnset($object);
    }

    public function offsetSet($object, $dumpedData = null) : void
    {
        $object = $object ?? $dumpedData;

        $this->add($object);
    }

    public function minus(Set $Storage) : void
    {
        foreach ($Storage as $object)
        {
            $this->remove($object);
        }
    }

    public function intersection(Set $Storage) : void
    {
        $this->Storage->intersection($Storage);
    }

    public function current()
    {
        return $this->Storage->current();
    }

    public function key() : int
    {
        return $this->Storage->key();
    }

    public function next() : void
    {
        $this->Storage->next();
    }

    public function rewind() : void
    {
        $this->Storage->rewind();
    }

    public function valid() : bool
    {
        return $this->Storage->valid();
    }

    public function isScalar() : bool
    {
        return $this->isScalar;
    }

    public static function isSetsOfSameType(Set $Set1, Set $Set2) : bool
    {
        return (
            $Set1->isScalar() === $Set2->isScalar()
            and $Set1->getSetType() === $Set2->getSetType()
        );
    }

    protected function isContainable($object) : bool
    {
        if ($this->isScalar)
        {
            return call_user_func($this->scalarTest, $object);
        }
        else
        {
            return $object instanceof $this->className;
        }
    }

    protected function isSetContainable(Set $storage) : bool
    {
        return self::isSetsOfSameType($this, $storage);
    }

    protected function isArrayContainable(array $array) : bool
    {
        foreach ($array as $object)
        {
            if ( ! $this->isContainable($object))
            {
                return false;
            }
        }

        return true;
    }

    protected function typeExceptionMsg(
        int    $argNum,
        string $methodName,
        $givenObject
    ) : string
    {
        $givenType = gettype($givenObject);

        return "Argument $argNum passed to $methodName() must be "
            . ($this->isScalar ? 'of type' : 'an instance of')
            . " {$this->className}, "
            . ($givenType === 'object' ? get_class($givenObject) : $givenType)
            . ' given. ';
    }

    protected function containerTypeExceptionMsg(
        int    $argNum,
        string $methodName
    ) : string
    {
        return "Argument $argNum passed to $methodName() must "
            . 'contain only '
            . ($this->isScalar ? 'type' : 'instances of')
            . " {$this->className}. ";
    }
}
