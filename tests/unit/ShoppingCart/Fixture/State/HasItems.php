<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\State;

use Pwm\SFlow\ShoppingCart\Fixture\Items;
use Pwm\SFlow\Name\NamedState;
use Pwm\SFlow\State;

final class HasItems implements State
{
    use NamedState;

    /** @var Items */
    private $items;

    public function __construct(Items $items)
    {
        $this->items = $items;
    }

    public function getItems(): Items
    {
        return $this->items;
    }
}
