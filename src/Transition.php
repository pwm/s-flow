<?php
declare(strict_types=1);

namespace Pwm\Fsm;

use Closure;
use Pwm\Fsm\Exception\IncompleteTransition;

class Transition
{
    /** @var string */
    private $event;
    /** @var string */
    private $from;
    /** @var string */
    private $to;
    /** @var null|Closure */
    private $condition;

    public function __construct(
        string $event,
        string $from = null,
        string $to = null,
        Closure $condition = null
    ) {
        $this->event = $event;
        $this->from = $from;
        $this->to = $to;
        $this->condition = $condition;
    }

    public function from(string $from): self
    {
        $this->from = $from;
        return $this;
    }

    public function to(string $to): self
    {
        $this->to = $to;
        return $this;
    }

    public function given(Closure $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function getFrom(): string
    {
        $this->ensureFromAndToStatesExists();
        return $this->from;
    }

    public function getTo(): string
    {
        $this->ensureFromAndToStatesExists();
        return $this->to;
    }

    public function getCondition(): ?Closure
    {
        $this->ensureFromAndToStatesExists();
        return $this->condition;
    }

    private function ensureFromAndToStatesExists(): void
    {
        if ($this->from === null || $this->to === null) {
            throw new IncompleteTransition('Transition must have a from and a to state.');
        }
    }
}
