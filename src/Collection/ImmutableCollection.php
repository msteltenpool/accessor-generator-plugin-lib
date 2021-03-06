<?php
namespace Hostnet\Component\AccessorGenerator\Collection;

use Closure;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;

/**
 * Wrapper for Doctrine collections to make them immutable. Implements the
 * ConstCollectionInterface for code completion and use in type hints.
 * Implements Collection for compatibility with Doctrine.
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class ImmutableCollection implements Collection, ConstCollectionInterface, Selectable
{
    /**
     * @var bool
     */
    private $is_clone = false;

    /**
     * @var Collection|Selectable
     */
    private $collection = null;

    /**
     * Wrap a collection to make it immutable.
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Do not use, this collection is immutable.
     *
     * {@inheritdoc}
     *
     * @throws \LogicException if the collection not cloned.
     */
    public function add($element)
    {
        if ($this->is_clone) {
            return $this->collection->add($element);
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * Do not use, this collection is immutable.
     *
     * {@inheritdoc}
     *
     * @throws \LogicException if the collection is not cloned.
     */
    public function clear()
    {
        if ($this->is_clone) {
            $this->collection->clear();
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function contains($element)
    {
        return $this->collection->contains($element);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->collection->isEmpty();
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritdoc}
     * @throws \LogicException if the collection is not cloned.
     */
    public function remove($key)
    {
        if ($this->is_clone) {
            return $this->collection->remove($key);
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritdoc}
     * @throws \LogicException if the collection is not cloned.
     */
    public function removeElement($element)
    {
        if ($this->is_clone) {
            return $this->collection->removeElement($element);
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function containsKey($key)
    {
        return $this->collection->containsKey($key);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return $this->collection->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys()
    {
        return $this->collection->getKeys();
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        return $this->collection->getValues();
    }

    public function set($key, $value)
    {
        if ($this->is_clone) {
            $this->collection->set($key, $value);
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->collection->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        return $this->collection->first();
    }

    /**
     * {@inheritdoc}
     */
    public function last()
    {
        return $this->collection->last();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->collection->key();
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->collection->current();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return $this->collection->next();
    }

    /**
     * {@inheritdoc}
     */
    public function exists(Closure $predicate)
    {
        return $this->collection->exists($predicate);
    }

    /**
     * {@inheritdoc}
     */
    public function filter(Closure $predicate)
    {
        return $this->collection->filter($predicate);
    }

    /**
     * {@inheritdoc}
     */
    public function forAll(Closure $predicate)
    {
        return $this->collection->forAll($predicate);
    }

    /**
     * {@inheritdoc}
     */
    public function map(Closure $func)
    {
        return $this->collection->map($func);
    }

    /**
     * {@inheritdoc}
     */
    public function partition(Closure $predicate)
    {
        return $this->collection->partition($predicate);
    }

    /**
     * {@inheritdoc}
     */
    public function indexOf($element)
    {
        return $this->collection->indexOf($element);
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null)
    {
        return $this->collection->slice($offset, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->collection->offsetExists($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->collection->offsetGet($offset);
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritdoc}
     * @throws \LogicException if the collection is not cloned.
     */
    public function offsetSet($offset, $value)
    {
        if ($this->is_clone) {
            $this->collection->offsetSet($offset, $value);
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * Do not use, this collection is immutable.
     * {@inheritdoc}
     * @throws \LogicException if the collection is not cloned.
     */
    public function offsetUnset($offset)
    {
        if ($this->is_clone) {
            $this->collection->offsetUnset($offset);
        } else {
            throw new \LogicException('This collection is immutable');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function matching(Criteria $criteria)
    {
        return $this->collection->matching($criteria);
    }

    public function __clone()
    {
        $this->collection = clone $this->collection;
        $this->is_clone   = true;
    }
}
