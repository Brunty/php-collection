<?php

namespace Brunty;

use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;
use ArrayAccess;

class Collection implements Countable, IteratorAggregate, ArrayAccess
{
    /**
     * @var array
     */
    protected $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @inheritdoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            return $this->add($value);
        }

        return $this->set($offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->items);
    }

    public function add($item): bool
    {
        $this->items[] = $item;

        return true;
    }

    public function map(Closure $closure): Collection
    {
        return new static(array_map($closure, $this->items));
    }

    public function filter(Closure $closure): Collection
    {
        return new static(array_filter($this->items, $closure));
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function remove($key)
    {
        if (isset($this->items[$key])) {
            $item = $this->items[$key];
            unset($this->items[$key]);

            return $item;
        }

        return false;
    }

    public function contains($item, bool $strict = false): bool
    {
        return in_array($item, $this->items, $strict);
    }

    public function containsKey($key): bool
    {
        return array_key_exists($key, $this->items);
    }

    public function get($key)
    {
        if ($this->containsKey($key)) {
            return $this->items[$key];
        }

        return null;
    }

    public function set($key, $value)
    {
        $this->items[$key] = $value;
    }

    public function clear()
    {
        $this->items = [];
    }

    public function slice($offset, $length = null): Collection
    {
        return new static(array_slice($this->items, $offset, $length, true));
    }
}
