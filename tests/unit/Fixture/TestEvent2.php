<?php
declare(strict_types=1);

namespace Pwm\SFlow\Fixture;

use Pwm\SFlow\Event;
use Pwm\SFlow\Name\NamedEvent;

final class TestEvent2 implements Event
{
    use NamedEvent;
}
