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
    protected $graph = [];

    public function __construct(string ...$states)
    {
        if ($states === []) {
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

    public function deriveState(string $startState, string ...$events): StateOp
    {
        return array_reduce($events, function (StateOp $stateOp, string $event): StateOp {
            return $stateOp->isSuccess()
                ? $this->transition($stateOp, $event)
                : $stateOp;
        }, StateOp::success($startState));
    }

    private function transition(StateOp $stateOp, string $event): StateOp
    {
        $from = $stateOp->getState();
        $events = array_merge($stateOp->getEvents(), [$event]);

        if (! isset($this->graph[$from][$event])) {
            return StateOp::failure($from, ...$events);
        }

        $transition = $this->graph[$from][$event];

        $condition = $transition->getCondition();
        if ($condition instanceof Closure) {
            return $condition()
                ? StateOp::success($transition->getTo(), ...$events)
                : StateOp::failure($from, ...$events);
        }

        return StateOp::success($transition->getTo(), ...$events);
    }
}
