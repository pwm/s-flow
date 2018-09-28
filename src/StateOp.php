<?php
declare(strict_types=1);

namespace Pwm\SFlow;

final class StateOp
{
    /** @var bool */
    private $success;
    /** @var string */
    private $state;
    /** @var array|string[] */
    private $events;

    public static function success(string $state, string ...$events): self
    {
        return new self(true, $state, $events);
    }

    public static function failure(string $state, string ...$events): self
    {
        return new self(false, $state, $events);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function getLastEvent(): string
    {
        return $this->events[count($this->events) - 1];
    }

    private function __construct(bool $success, string $state, array $events)
    {
        $this->success = $success;
        $this->state = $state;
        $this->events = $events;
    }
}
