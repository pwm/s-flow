<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use Pwm\SFlow\Name\EventName;

interface Event
{
    public static function name(): EventName;
}
