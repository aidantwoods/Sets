<?php
declare(strict_types=1);

namespace Aidantwoods\Sets\Sets;

use Aidantwoods\Sets\AbstractSetOps;
use Aidantwoods\Sets\Set;

use SplObjectStorage;
use InvalidArgumentException;
use LogicException;

/**
 * A Set is like SplObjectStorage, except the type of the contained objects
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
            throw new LogicException('May not redefine set type');
        }

        $lowerClassName = strtolower($className);

        if (in_array($lowerClassName, self::SCALARS, true))
        {
            $this->isScalar   = true;
            $this->className  = $lowerClassName;
            $this->scalarTest = "is_{$this->className}";
        }
        else
        {
            $this->className = $className;
        }

        $this->SplObjectStorage = new SplObjectStorage;
    }

    public function addAll(Set $storage) : void
    {
        if ($this->isSetContainable($storage))
        {
            $this->SplObjectStorage->addAll($storage);
        }
        else
        {
            throw new InvalidArgumentException(
                $this->containerTypeExceptionMsg(1, __METHOD__)
            );
        }
    }

    public function addArray(array $storage) : void
    {
        if ($this->isArrayContainable($storage))
        {
            $this->addMultiple($storage);
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

        $this->SplObjectStorage->attach($this->val($object));
    }

    protected function addMultiple($Storage) : void
    {
        foreach ($storage as $object)
        {
            $this->add($object);
        }
    }

    protected function val($object)
    {
        return $this->isScalar ? $object : new PseudoObject($object);
    }

    public function offsetGet($object)
    {
        if ($this->contains($this->val($object)))
        {
            # we do not associate data with objects in a set
            return $object;
        }
        else
        {
            # for standard exeception
            return $this->SplObjectStorage->offsetGet($this->val($object));
        }
    }

    public function getInfo()
    {
        return $this->current();
    }

    public function offsetSet($object, $data = null) : void
    {
        $this->add($object);
    }

    public function getSetType() : string
    {
        return $this->className;
    }


    public function isScalar() : bool
    {
        return $this->isScalar;
    }

    public function subset(callback $suchThat) : Set
    {
        return $this->filter($suchThat);
    }

    public function count() : int
    {
        return $this->SplObjectStorage->count();
    }

    public function current()
    {
        return $this->SplObjectStorage->current();
    }

    public function next()
    {
        return $this->SplObjectStorage->next();
    }

    public function key()
    {
        return $this->SplObjectStorage->key();
    }

    public function valid()
    {
        return $this->SplObjectStorage->valid();
    }

    public function rewind()
    {
        return $this->SplObjectStorage->rewind();
    }

    public function offsetExists($offset)
    {
        return $this->SplObjectStorage->offsetExists($offset);
    }

    public function offsetUnset($offset)
    {
        return $this->SplObjectStorage->offsetUnset($offset);
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
        $givenType = gettype($givenObject);

        return "Argument $argNum passed to $methodName() must "
            . 'contain only '
            . ($this->isScalar ? 'type' : 'instances of')
            . " {$this->className}. ";
    }
}
