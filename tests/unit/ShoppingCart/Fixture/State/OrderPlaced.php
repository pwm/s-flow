<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\State;

use Pwm\SFlow\Name\NamedState;
use Pwm\SFlow\State;

final class OrderPlaced implements State
{
    use NamedState;
}
