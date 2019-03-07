<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use PHPUnit\Framework\TestCase;
use Pwm\SFlow\Exception\IncompleteArrow;
use Pwm\SFlow\Fixture\NoopTransition;
use Pwm\SFlow\Name\EventName;
use Pwm\SFlow\Name\StateName;
use Throwable;

final class ArrowTest extends TestCase
{
    /**
     * @test
     */
    public function it_builds_an_arrow(): void
    {
        $drawnArrow = (new Arrow(new EventName('someEvent')))
            ->from(new StateName('fromState'))
            ->via(new NoopTransition)
            ->draw();

        self::assertInstanceOf(DrawnArrow::class, $drawnArrow);
        self::assertSame('someEvent', $drawnArrow->getEventName()->unWrap());
        self::assertSame('fromState', $drawnArrow->getStateName()->unWrap());
        self::assertInstanceOf(Transition::class, $drawnArrow->getTransition());
    }

    /**
     * @test
     */
    public function it_throws_on_incomplete_draw(): void
    {
        /** @var Arrow[] $incompleteArrows */
        $incompleteArrows = [
            new Arrow(new EventName('someEvent')),
            (new Arrow(new EventName('someEvent')))->from(new StateName('fromState')),
            (new Arrow(new EventName('someEvent')))->via(new NoopTransition),
        ];

        foreach ($incompleteArrows as $incompleteArrow) {
            try {
                $incompleteArrow->draw();
                self::assertTrue(false);
            } catch (Throwable $e) {
                self::assertInstanceOf(IncompleteArrow::class, $e);
            }
        }
    }
}
