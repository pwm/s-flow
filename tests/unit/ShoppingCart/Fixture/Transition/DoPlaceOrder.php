<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\Transition;

use Pwm\SFlow\Event;
use Pwm\SFlow\State;
use Pwm\SFlow\Transition;
use Pwm\SFlow\ShoppingCart\Fixture\Event\PlaceOrder;
use Pwm\SFlow\ShoppingCart\Fixture\State\CardConfirmed;
use Pwm\SFlow\ShoppingCart\Fixture\State\OrderPlaced;

// CardConfirmed -> PlaceOrder -> OrderPlaced
final class DoPlaceOrder implements Transition
{
    public function __invoke(State $state, Event $event): State
    {
        /**
         * @var CardConfirmed $state
         * @var PlaceOrder $event
         */
        return $this->transition($state, $event);
    }

    public function transition(CardConfirmed $state, PlaceOrder $event): OrderPlaced
    {
        return new OrderPlaced();
    }
}
