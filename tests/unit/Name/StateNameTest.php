<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use PHPUnit\Framework\TestCase;
use Pwm\SFlow\Name\StateName;

final class StateNameTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $stateName = new StateName('someState');

        self::assertInstanceOf(StateName::class, $stateName);
        self::assertSame('someState', $stateName->unWrap());
    }
}
