<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use Countable;
use Generator;
use IteratorAggregate;
use function array_values;
use function count;

final class Events implements Countable, IteratorAggregate
{
    /** @var Event[] */
    private $events;
    /** @var int */
    private $count;

    public function __construct(Event ...$events)
    {
        $this->events = array_values($events);
        $this->count = count($this->events);
    }

    public function addEvent(Event $event): self
    {
        return new self(...array_merge($this->events, [$event]));
    }

    public function toList(): array
    {
        return $this->events;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function getIterator(): Generator
    {
        yield from $this->events;
    }
}
