<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use Pwm\SFlow\Exception\IncompleteArrow;
use Pwm\SFlow\Name\EventName;
use Pwm\SFlow\Name\StateName;

final class Arrow
{
    /** @var EventName */
    private $eventName;
    /** @var null|StateName */
    private $stateName;
    /** @var null|Transition */
    private $transition;

    public function __construct(EventName $eventName)
    {
        $this->eventName = $eventName;
    }

    public function from(StateName $stateName): self
    {
        $this->stateName = $stateName;
        return $this;
    }

    public function via(Transition $transition): self
    {
        $this->transition = $transition;
        return $this;
    }

    public function draw(): DrawnArrow
    {
        if (! $this->stateName instanceof StateName || ! $this->transition instanceof Transition) {
            throw new IncompleteArrow('Arrow must be fully defined in order to draw it.');
        }

        return new DrawnArrow(
            $this->eventName,
            $this->stateName,
            $this->transition
        );
    }
}
