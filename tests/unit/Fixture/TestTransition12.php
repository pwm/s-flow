<?php
declare(strict_types=1);

namespace Pwm\SFlow\Fixture;

use Pwm\SFlow\Event;
use Pwm\SFlow\State;
use Pwm\SFlow\Transition;

final class TestTransition12 implements Transition
{
    public function __invoke(State $state, Event $event): State
    {
        /**
         * @var TestState1 $state
         * @var TestEvent1 $event
         */
        return $this->transition($state, $event);
    }

    private function transition(TestState1 $state, TestEvent1 $event): TestState2
    {
        return new TestState2;
    }
}
