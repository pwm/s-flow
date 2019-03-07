<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use function array_reduce;

final class FSM
{
    /** @var Graph */
    private $graph;

    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }

    public function run(State $state, Events $events): TransitionOp
    {
        return array_reduce($events->toList(), function (TransitionOp $transitionOp, Event $event): TransitionOp {
            return $transitionOp->isSuccess()
                ? $this->transition($transitionOp, $event)
                : $transitionOp;
        }, TransitionOp::success($state, new Events()));
    }

    private function transition(TransitionOp $transitionOp, Event $event): TransitionOp
    {
        $state = $transitionOp->getState();
        $events = $transitionOp->getEvents()->addEvent($event);
        $transition = $this->graph->getTransition($state, $event);

        return $transition instanceof Transition
            ? TransitionOp::success($transition($state, $event), $events)
            : TransitionOp::failure($state, $events);
    }
}
