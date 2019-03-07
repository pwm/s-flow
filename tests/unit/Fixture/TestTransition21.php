<?php
declare(strict_types=1);

namespace Pwm\SFlow\Fixture;

use Pwm\SFlow\Event;
use Pwm\SFlow\State;
use Pwm\SFlow\Transition;

final class TestTransition21 implements Transition
{
    public function __invoke(State $state, Event $event): State
    {
        /**
         * @var TestState2 $state
         * @var TestEvent2 $event
         */
        return $this->transition($state, $event);
    }

    private function transition(TestState2 $state, TestEvent2 $event): TestState1
    {
        return new TestState1;
    }
}
