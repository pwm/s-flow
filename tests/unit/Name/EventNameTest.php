<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use PHPUnit\Framework\TestCase;
use Pwm\SFlow\Name\EventName;

final class EventNameTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $eventName = new EventName('someEvent');

        self::assertInstanceOf(EventName::class, $eventName);
        self::assertSame('someEvent', $eventName->unWrap());
    }
}
