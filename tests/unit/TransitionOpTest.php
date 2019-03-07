<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use PHPUnit\Framework\TestCase;
use Pwm\SFlow\Fixture\TestEvent1;
use Pwm\SFlow\Fixture\TestEvent2;
use Pwm\SFlow\Fixture\TestState1;

final class TransitionOpTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_from_success(): void
    {
        $transitionOp = TransitionOp::success(
            new TestState1,
            new Events(new TestEvent1, new TestEvent2)
        );

        self::assertInstanceOf(TransitionOp::class, $transitionOp);
        self::assertTrue($transitionOp->isSuccess());
        self::assertSame(TestState1::class, $transitionOp->getState()::name()->unWrap());
        self::assertSame([TestEvent1::class, TestEvent2::class], array_map(function (Event $event): string {
            return $event::name()->unWrap();
        }, $transitionOp->getEvents()->toList()));
        self::assertSame(TestEvent2::class, $transitionOp->getLastEvent()::name()->unWrap());
    }

    /**
     * @test
     */
    public function it_creates_from_failure(): void
    {
        $transitionOp = TransitionOp::failure(
            new TestState1,
            new Events(new TestEvent1, new TestEvent2)
        );

        self::assertInstanceOf(TransitionOp::class, $transitionOp);
        self::assertFalse($transitionOp->isSuccess());
        self::assertSame(TestState1::class, $transitionOp->getState()::name()->unWrap());
        self::assertSame([TestEvent1::class, TestEvent2::class], array_map(function (Event $event): string {
            return $event::name()->unWrap();
        }, $transitionOp->getEvents()->toList()));
        self::assertSame(TestEvent2::class, $transitionOp->getLastEvent()::name()->unWrap());
    }
}
