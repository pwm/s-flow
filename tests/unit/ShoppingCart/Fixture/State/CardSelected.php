<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\State;

use Pwm\SFlow\ShoppingCart\Fixture\Card;
use Pwm\SFlow\ShoppingCart\Fixture\Items;
use Pwm\SFlow\Name\NamedState;
use Pwm\SFlow\State;

final class CardSelected implements State
{
    use NamedState;

    /** @var Items */
    private $items;
    /** @var Card */
    private $card;

    public function __construct(Items $items, Card $card)
    {
        $this->items = $items;
        $this->card = $card;
    }

    public function getItems(): Items
    {
        return $this->items;
    }

    public function getCard(): Card
    {
        return $this->card;
    }
}
