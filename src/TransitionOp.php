<?php
declare(strict_types=1);

namespace Pwm\SFlow;

final class TransitionOp
{
    /** @var bool */
    private $success;
    /** @var State */
    private $state;
    /** @var Events */
    private $events;

    public static function success(State $state, Events $events): self
    {
        return new self(true, $state, $events);
    }

    public static function failure(State $state, Events $events): self
    {
        return new self(false, $state, $events);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function getEvents(): Events
    {
        return $this->events;
    }

    public function getLastEvent(): ?Event
    {
        /** @var Event[] $events */
        $events = $this->events->toList();
        return $events[$this->events->count() - 1] ?? null;
    }

    private function __construct(
        bool $success,
        State $state,
        Events $events
    ) {
        $this->success = $success;
        $this->state = $state;
        $this->events = $events;
    }
}
