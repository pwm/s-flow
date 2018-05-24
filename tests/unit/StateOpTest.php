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
        $stateOp = StateOp::success('yes');

        self::assertInstanceOf(StateOp::class, $stateOp);
        self::assertTrue($stateOp->isSuccess());
        self::assertSame('yes', $stateOp->getState());
    }

    /**
     * @test
     */
    public function it_creates_from_failure(): void
    {
        $stateOp = StateOp::failure('no');

        self::assertInstanceOf(StateOp::class, $stateOp);
        self::assertFalse($stateOp->isSuccess());
        self::assertSame('no', $stateOp->getState());
    }
}
