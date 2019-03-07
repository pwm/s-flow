<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use PHPUnit\Framework\TestCase;
use Pwm\SFlow\Fixture\TestEvent1;
use Pwm\SFlow\Fixture\TestEvent2;
use Pwm\SFlow\Fixture\TestGraph;
use Pwm\SFlow\Fixture\TestState1;
use Pwm\SFlow\Fixture\TestState2;

final class FSMTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_from_a_graph(): void
    {
        $fsm = new FSM(TestGraph::create());

        self::assertInstanceOf(FSM::class, $fsm);
    }

    /**
     * @test
     */
    public function it_can_be_run(): void
    {
        $fsm = new FSM(TestGraph::create());

        // 0 step

        $transitionOp = $fsm->run(
            new TestState1,
            new Events()
        );

        self::assertInstanceOf(TransitionOp::class, $transitionOp);
        self::assertTrue($transitionOp->isSuccess());
        self::assertSame(TestState1::class, $transitionOp->getState()::name()->unWrap());
        self::assertSame([], $transitionOp->getEvents()->toList());
        self::assertNull($transitionOp->getLastEvent());

        // 1 step

        $transitionOp = $fsm->run(
            new TestState1,
            new Events(
                new TestEvent1
            )
        );

        self::assertInstanceOf(TransitionOp::class, $transitionOp);
        self::assertTrue($transitionOp->isSuccess());
        self::assertSame(TestState2::class, $transitionOp->getState()::name()->unWrap());
        self::assertSame([TestEvent1::class], array_map(function (Event $event): string {
            return $event::name()->unWrap();
        }, $transitionOp->getEvents()->toList()));
        self::assertSame(TestEvent1::class, $transitionOp->getLastEvent()::name()->unWrap());

        // 2 steps

        $transitionOp = $fsm->run(
            new TestState1,
            new Events(
                new TestEvent1,
                new TestEvent2
            )
        );

        self::assertInstanceOf(TransitionOp::class, $transitionOp);
        self::assertTrue($transitionOp->isSuccess());
        self::assertSame(TestState1::class, $transitionOp->getState()::name()->unWrap());
        self::assertSame([TestEvent1::class, TestEvent2::class], array_map(function (Event $event): string {
            return $event::name()->unWrap();
        }, $transitionOp->getEvents()->toList()));
        self::assertSame(TestEvent2::class, $transitionOp->getLastEvent()::name()->unWrap());
    }
}
