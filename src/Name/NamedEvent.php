<?php
declare(strict_types=1);

namespace Pwm\SFlow\Name;

trait NamedEvent
{
    public static function name(): EventName
    {
        return new EventName(self::class);
    }
}
