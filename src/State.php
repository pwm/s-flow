<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use Pwm\SFlow\Name\StateName;

interface State
{
    public static function name(): StateName;
}
