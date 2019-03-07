<?php
declare(strict_types=1);

namespace Pwm\SFlow\Fixture;

use Pwm\SFlow\Event;
use Pwm\SFlow\State;
use Pwm\SFlow\Transition;

final class NoopTransition implements Transition
{
    public function __invoke(State $state, Event $event): State
    {
        return $state;
    }
}
