<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use PHPUnit\Framework\TestCase;
use Pwm\SFlow\Fixture\NoopTransition;
use Pwm\SFlow\Name\EventName;
use Pwm\SFlow\Name\StateName;

final class DrawnArrowTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $drawnArrow = new DrawnArrow(
            new EventName('someEvent'),
            new StateName('fromState'),
            new NoopTransition
        );

        self::assertInstanceOf(DrawnArrow::class, $drawnArrow);
        self::assertSame('someEvent', $drawnArrow->getEventName()->unWrap());
        self::assertSame('fromState', $drawnArrow->getStateName()->unWrap());
        self::assertInstanceOf(Transition::class, $drawnArrow->getTransition());
    }
}
