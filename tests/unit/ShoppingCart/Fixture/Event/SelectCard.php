<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\Event;

use Pwm\SFlow\Event;
use Pwm\SFlow\ShoppingCart\Fixture\Card;
use Pwm\SFlow\Name\NamedEvent;

final class SelectCard implements Event
{
    use NamedEvent;

    /** @var Card */
    private $card;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    public function getCard(): Card
    {
        return $this->card;
    }
}
