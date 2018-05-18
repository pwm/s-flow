<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use Closure;
use Pwm\SFlow\Exception\DuplicateEvent;
use Pwm\SFlow\Exception\MissingState;
use function array_reduce;
use function count;
use function sprintf;

class FSM
{
    /** @var Transition[][] */
    private $graph = [];

    public function __construct(array $states)
    {
        if (count($states) === 0) {
            throw new MissingState('There must be at least one state.');
        }

        foreach ($states as $state) {
            $this->graph[$state] = [];
        }
    }

    public function addTransition(Transition $transition): self
    {
        $event = $transition->getEvent();
        $from = $transition->getFrom();
        $to = $transition->getTo();

        if (! isset($this->graph[$from])) {
            throw new MissingState(sprintf('From state %s is not an element of the defined states.', $from));
        }
        if (! isset($this->graph[$to])) {
            throw new MissingState(sprintf('To state %s is not an element of the defined states.', $to));
        }
        if (isset($this->graph[$from][$event])) {
            throw new DuplicateEvent(sprintf('Duplicate event %s between states %s and %s.', $event, $from, $to));
        }

        $this->graph[$from][$event] = $transition;

        return $this;
    }

    public function deriveState(string $state, array $events): string
    {
        return array_reduce($events, function (string $state, string $event): string {
            return $this->transition($state, $event);
        }, $state);
    }

    private function transition(string $from, string $event): string
    {
        if (! isset($this->graph[$from][$event])) {
            return $from;
        }

        $transition = $this->graph[$from][$event];

        $condition = $transition->getCondition();
        if ($condition instanceof Closure) {
            return $condition()
                ? $transition->getTo()
                : $from;
        }

        return $transition->getTo();
    }
}
