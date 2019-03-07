<?php
declare(strict_types=1);

namespace Pwm\SFlow;

interface Transition
{
    public function __invoke(State $state, Event $event): State;
}
