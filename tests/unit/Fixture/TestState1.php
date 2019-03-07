<?php
declare(strict_types=1);

namespace Pwm\SFlow\Fixture;

use Pwm\SFlow\State;
use Pwm\SFlow\Name\NamedState;

final class TestState1 implements State
{
    use NamedState;
}
