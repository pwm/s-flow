<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use Pwm\SFlow\Exception\DuplicateEvent;
use Pwm\SFlow\Exception\DuplicateState;
use Pwm\SFlow\Exception\MissingState;
use Pwm\SFlow\Name\StateName;
use function array_reduce;
use function sprintf;

final class Graph
{
    /** @var Transition[][] */
    private $graph = [];

    public function __construct(StateName ...$stateNames)
    {
        foreach ($stateNames as $stateName) {
            if (isset($this->graph[$stateName->unWrap()])) {
                throw new DuplicateState(sprintf('Duplicate State %s.', $stateName->unWrap()));
            }
            $this->graph[$stateName->unWrap()] = [];
        }
    }

    public function drawArrows(Arrow ...$arrows): self
    {
        return array_reduce($arrows, function (self $graph, Arrow $arrow): self {
            return $graph->drawArrow($arrow);
        }, $this);
    }

    public function drawArrow(Arrow $arrow): self
    {
        return $this->addArrow($arrow->draw());
    }

    public function addArrows(DrawnArrow ...$drawnArrows): self
    {
        return array_reduce($drawnArrows, function (self $graph, DrawnArrow $drawnArrow): self {
            return $graph->addArrow($drawnArrow);
        }, $this);
    }

    public function addArrow(DrawnArrow $drawnArrow): self
    {
        $stateName = $drawnArrow->getStateName()->unWrap();
        $eventName = $drawnArrow->getEventName()->unWrap();

        if (! isset($this->graph[$stateName])) {
            throw new MissingState(sprintf('State %s is unknown.', $stateName));
        }
        if (isset($this->graph[$stateName][$eventName])) {
            throw new DuplicateEvent(sprintf('Duplicate Event %s from state %s.', $eventName, $stateName));
        }

        $this->graph[$stateName][$eventName] = $drawnArrow->getTransition();

        return $this;
    }

    public function getTransition(State $state, Event $event): ?Transition
    {
        return $this->graph[$state::name()->unWrap()][$event::name()->unWrap()] ?? null;
    }
}
