<?php
declare(strict_types=1);

namespace Pwm\SFlow\Fixture;

use Pwm\SFlow\Event;
use Pwm\SFlow\Name\NamedEvent;

final class TestEvent1 implements Event
{
    use NamedEvent;
}
