<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use Pwm\SFlow\Name\EventName;
use Pwm\SFlow\Name\StateName;

final class DrawnArrow
{
    /** @var EventName */
    private $eventName;
    /** @var StateName */
    private $stateName;
    /** @var Transition */
    private $transition;

    public function __construct(
        EventName $eventName,
        StateName $stateName,
        Transition $transition
    ) {
        $this->eventName = $eventName;
        $this->stateName = $stateName;
        $this->transition = $transition;
    }

    public function getEventName(): EventName
    {
        return $this->eventName;
    }

    public function getStateName(): StateName
    {
        return $this->stateName;
    }

    public function getTransition(): Transition
    {
        return $this->transition;
    }
}
