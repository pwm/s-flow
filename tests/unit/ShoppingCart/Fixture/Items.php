<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture;

use Countable;
use Generator;
use IteratorAggregate;

final class Items implements Countable, IteratorAggregate
{
    /** @var Item[] */
    private $items;
    /** @var int */
    private $count;

    public function __construct(Item $item, Item ...$items)
    {
        $this->items = array_merge([$item], array_values($items));
        $this->count = count($this->items);
    }

    public function toList(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function getIterator(): Generator
    {
        yield from $this->items;
    }
}
