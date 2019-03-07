<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\Event;

use Pwm\SFlow\Event;
use Pwm\SFlow\ShoppingCart\Fixture\Item;
use Pwm\SFlow\Name\NamedEvent;

final class Select implements Event
{
    use NamedEvent;

    /** @var Item */
    private $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function getItem(): Item
    {
        return $this->item;
    }
}
