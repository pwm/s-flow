<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use PHPUnit\Framework\TestCase;

final class StateOpTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_from_success(): void
    {
        $stateOp = StateOp::success('yes', ['E1', 'E2', 'E3']);

        self::assertInstanceOf(StateOp::class, $stateOp);
        self::assertTrue($stateOp->isSuccess());
        self::assertSame('yes', $stateOp->getState());
        self::assertSame(['E1', 'E2', 'E3'], $stateOp->getEvents());
    }

    /**
     * @test
     */
    public function it_creates_from_failure(): void
    {
        $stateOp = StateOp::failure('no', ['E1', 'E2', 'E3']);

        self::assertInstanceOf(StateOp::class, $stateOp);
        self::assertFalse($stateOp->isSuccess());
        self::assertSame('no', $stateOp->getState());
        self::assertSame(['E1', 'E2', 'E3'], $stateOp->getEvents());
    }
}
