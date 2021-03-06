<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\Event;

use Pwm\SFlow\Event;
use Pwm\SFlow\Name\NamedEvent;

final class Checkout implements Event
{
    use NamedEvent;
}
