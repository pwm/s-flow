<?php
declare(strict_types=1);

namespace Pwm\SFlow\Name;

trait NamedState
{
    public static function name(): StateName
    {
        return new StateName(self::class);
    }
}
