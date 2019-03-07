<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use PHPUnit\Framework\TestCase;
use Pwm\SFlow\Fixture\TestEvent1;
use Pwm\SFlow\Fixture\TestEvent2;

final class EventsTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_empty(): void
    {
        $events = new Events();

        self::assertInstanceOf(Events::class, $events);
        self::assertCount(0, $events);
        self::assertSame([], $events->toList());
    }

    /**
     * @test
     */
    public function it_creates(): void
    {
        $events = new Events(new TestEvent1, new TestEvent2);

        self::assertInstanceOf(Events::class, $events);
        self::assertCount(2, $events);

        self::assertSame([TestEvent1::class, TestEvent2::class], array_map(function (Event $event): string {
            return $event::name()->unWrap();
        }, $events->toList()));

        foreach ($events as $event) {
            self::assertInstanceOf(Event::class, $event);
        }
    }

    /**
     * @test
     */
    public function new_event_can_be_added(): void
    {
        $events = new Events(new TestEvent1);

        self::assertInstanceOf(Events::class, $events);
        self::assertCount(1, $events);

        $events = $events->addEvent(new TestEvent2);

        self::assertInstanceOf(Events::class, $events);
        self::assertCount(2, $events);
    }
}
