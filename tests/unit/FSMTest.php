<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use PHPUnit\Framework\TestCase;

final class FSMTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        self::assertInstanceOf(FSM::class, new FSM(['S1', 'S2', 'S3']));
    }

    /**
     * @test
     * @expectedException \Pwm\SFlow\Exception\MissingState
     */
    public function it_throws_when_creating_with_no_states(): void
    {
        new FSM([]);
    }

    /**
     * @test
     * @expectedException \Pwm\SFlow\Exception\MissingState
     */
    public function it_throws_if_adding_a_transition_with_a_nonexistent_from_state(): void
    {
        (new FSM(['S1', 'S2']))->addTransition((new Transition('E1'))->from('X')->to('S2'));
    }

    /**
     * @test
     * @expectedException \Pwm\SFlow\Exception\MissingState
     */
    public function it_throws_if_adding_a_transition_with_a_nonexistent_to_state(): void
    {
        (new FSM(['S1', 'S2']))->addTransition((new Transition('E1'))->from('S1')->to('X'));
    }

    /**
     * @test
     * @expectedException \Pwm\SFlow\Exception\DuplicateEvent
     */
    public function it_throws_on_duplicate_transitions_between_states(): void
    {
        $fsm = new FSM(['S1', 'S2']);
        $transition = (new Transition('E1'))->from('S1')->to('S2');
        $fsm->addTransition($transition);
        $fsm->addTransition($transition);
    }

    /**
     * @test
     */
    public function it_transitions_between_states(): void
    {
        $fsm = (new FSM(['S1', 'S2', 'S3']))
            ->addTransition((new Transition('E1'))->from('S1')->to('S2'))
            ->addTransition((new Transition('E2'))->from('S2')->to('S3'));

        $op = $fsm->deriveState('S1', []);
        self::assertTrue($op->isSuccess());
        self::assertSame('S1', $op->getState());
        self::assertSame([], $op->getEvents());

        $op = $fsm->deriveState('S1', ['E1']);
        self::assertTrue($op->isSuccess());
        self::assertSame('S2', $op->getState());
        self::assertSame(['E1'], $op->getEvents());

        $op = $fsm->deriveState('S1', ['E1', 'E2']);
        self::assertTrue($op->isSuccess());
        self::assertSame('S3', $op->getState());
        self::assertSame(['E1', 'E2'], $op->getEvents());

        $op = $fsm->deriveState('S2', []);
        self::assertTrue($op->isSuccess());
        self::assertSame('S2', $op->getState());
        self::assertSame([], $op->getEvents());

        $op = $fsm->deriveState('S2', ['E2']);
        self::assertTrue($op->isSuccess());
        self::assertSame('S3', $op->getState());
        self::assertSame(['E2'], $op->getEvents());

        $op = $fsm->deriveState('S3', []);
        self::assertTrue($op->isSuccess());
        self::assertSame('S3', $op->getState());
        self::assertSame([], $op->getEvents());
    }

    /**
     * @test
     */
    public function the_same_event_can_be_reused_for_different_transitions(): void
    {
        $event = 'E1';

        $fsm = (new FSM(['S1', 'S2', 'S3']))
            ->addTransition((new Transition($event))->from('S1')->to('S2'))
            ->addTransition((new Transition($event))->from('S2')->to('S3'))
            ->addTransition((new Transition($event))->from('S3')->to('S1'));

        $op = $fsm->deriveState('S1', []);
        self::assertTrue($op->isSuccess());
        self::assertSame('S1', $op->getState());
        self::assertSame([], $op->getEvents());

        $op = $fsm->deriveState('S1', ['E1']);
        self::assertTrue($op->isSuccess());
        self::assertSame('S2', $op->getState());
        self::assertSame(['E1'], $op->getEvents());

        $op = $fsm->deriveState('S1', ['E1', 'E1']);
        self::assertTrue($op->isSuccess());
        self::assertSame('S3', $op->getState());
        self::assertSame(['E1', 'E1'], $op->getEvents());

        $op = $fsm->deriveState('S1', ['E1', 'E1', 'E1']);
        self::assertTrue($op->isSuccess());
        self::assertSame('S1', $op->getState());
        self::assertSame(['E1', 'E1', 'E1'], $op->getEvents());
    }

    /**
     * @test
     */
    public function it_does_not_change_state_if_the_transition_does_not_belong_to_the_start_state(): void
    {
        $fsm = (new FSM(['S1', 'S2', 'S3']))
            ->addTransition((new Transition('E1'))->from('S2')->to('S3'));

        $op = $fsm->deriveState('S1', ['E1']);
        self::assertFalse($op->isSuccess());
        self::assertSame('S1', $op->getState());
        self::assertSame([], $op->getEvents());
    }

    /**
     * @test
     */
    public function transitions_can_be_conditional(): void
    {
        $always = function () { return true; };
        $never = function () { return false; };

        $fsm = (new FSM(['S1', 'S2', 'S3']))
            ->addTransition((new Transition('E1'))->from('S1')->given($always)->to('S2'))
            ->addTransition((new Transition('E2'))->from('S2')->given($never)->to('S3'));

        // can always go S1 --E1--> S2
        $op = $fsm->deriveState('S1', ['E1']);
        self::assertTrue($op->isSuccess());
        self::assertSame('S2', $op->getState());
        self::assertSame(['E1'], $op->getEvents());

        // can never go S2 --E2--> S3
        $op = $fsm->deriveState('S2', ['E2']);
        self::assertFalse($op->isSuccess());
        self::assertSame('S2', $op->getState());
        self::assertSame([], $op->getEvents());
    }
}
