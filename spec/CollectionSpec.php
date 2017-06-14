<?php

use Brunty\Collection;

describe('Collection', function() {
    it('implements ArrayAccess', function() {
        expect(new Collection)->toBeAnInstanceOf(ArrayAccess::class);
    });

    it('implements IteratorAggregate', function() {
        expect(new Collection)->toBeAnInstanceOf(IteratorAggregate::class);
    });

    it('implements Countable', function() {
        expect(new Collection)->toBeAnInstanceOf(Countable::class);
    });

    it('implements the ArrayAccess methods correctly', function() {
        $collection = new Collection(['item1', 'item2', 'foo' => 'bar', 'baz' => 'bingo', 'unsetthis' => 'thing']);

        $collection['ping'] = 'pong';
        $collection['baz'] = 'boz';
        $collection[] = 'appended';
        unset($collection['unsetthis']);

        expect($collection['ping'])->toBe('pong');
        expect($collection['thiskeydoesnotexist'])->toBeNull();
        expect($collection['unsetthis'])->toBeNull();
    });

    it('can check whether things are set as if it were an array', function() {
        $collection = new Collection(['foo' => 'bar']);

        expect(isset($collection['foo']))->toBe(true);
        expect(isset($collection['thiskeydoesnotexist']))->toBe(false);
    });

    it('can add items to the collection like it were an array', function() {
        $collection = new Collection(['item1', 'item2', 'foo' => 'bar']);
        $collection['ping'] = 'pong';

        expect($collection['ping'])->toBe('pong');
    });

    it('can unset items in the collection like it were an array', function() {
        $collection = new Collection(['item1', 'item2', 'foo' => 'bar']);
        unset($collection['foo']);

        expect($collection['foo'])->toBeNull();
        expect($collection->toArray())->toBe(['item1', 'item2']);
    });

    it('returns an iterator with the correct collection items', function() {
        $collection = new Collection(['item1', 'item2']);

        expect($collection->getIterator())->toBeAnInstanceOf(\ArrayIterator::class);
        expect($collection->getIterator())->toHaveLength(2);
    });

    it('is countable', function() {
        $collection = new Collection(['item1', 'item2']);

        expect($collection)->toHaveLength(2);
    });

    it('returns the collection as an array', function() {
        $array = ['foo' => ['bar' => 'baz']];
        $collection = new Collection($array);

        expect($collection->toArray())->toBe($array);
    });

    it('can add an item to the collection', function() {
        $collection = new Collection;
        $collection->add('item');

        expect($collection->toArray())->toBe(['item']);
    });

    it('can remove an item from the collection by an unspecified key', function() {
        $collection = new Collection(['item1', 'foo' => 'bar']);
        $item = $collection->remove(0);

        expect($item)->toBe('item1');
        expect($collection->get('item1'))->toBeNull();
        expect($collection->toArray())->toBe(['foo' => 'bar']);
    });

    it('can remove an item from the collection by a specified key', function() {
        $collection = new Collection(['item1', 'foo' => 'bar']);
        $item = $collection->remove('foo');

        expect($item)->toBe('bar');
        expect($collection->get('foo'))->toBeNull();
        expect($collection->toArray())->toBe(['item1']);
    });

    it('maps a function to the collection', function() {
        $collection = new Collection(['item1', 'item2']);
        $results = $collection->map(
            function ($item) {
                return $item . 's';
            }
        );

        expect($results->toArray())->toBe(['item1s', 'item2s']);
        expect($results)->toBeAnInstanceOf(Collection::class);
        expect($results)->not->toBe($collection); // ensure that we have a new object back
    });

    it('filters the collection', function() {
        $collection = new Collection(['item1', 'item2']);
        $results = $collection->filter(
            function ($item) {
                return $item === 'item1';
            }
        );

        expect($results->toArray())->toBe(['item1']);
        expect($results)->toBeAnInstanceOf(Collection::class);
        expect($results)->not->toBe($collection); // ensure that we have a new object back
    });

    it('clears the collection', function() {
        $collection = new Collection(['item1', 'item2']);
        $collection->clear();

        expect($collection->toArray())->toBe([]);
    });

    it('can chain multiple methods together', function() {
        $collection = new Collection(['foo' => 'bar', 'bar' => 'baz']);
        $results = $collection->map(
            function ($value) {
                return $value . 's';
            }
        )->filter(
            function ($value) {
                return $value === 'bars';
            }
        );

        expect($results->toArray())->toBe(['foo' => 'bars']);
        expect($results)->toBeAnInstanceOf(Collection::class);
        expect($results)->not->toBe($collection); // ensure that we have a new object back
    });

    it('checks whether the collection contains an item', function() {
        $collection = new Collection(['item1', 'foo' => 'bar']);

        expect($collection->contains('bar'))->toBe(true);
        expect($collection->contains('baz'))->toBe(false);
    });

    it('slices the collection', function() {
        $collection = new Collection(['item1', 'item2', 'foo' => 'bar', 'item3']);
        $results = $collection->slice(0, 3);

        expect($results->toArray())->toBe(['item1', 'item2', 'foo' => 'bar']);
        expect($results)->toBeAnInstanceOf(Collection::class);
        expect($results)->not->toBe($collection); // ensure that we have a new object back
    });
});
