<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\Transition;

use Pwm\SFlow\Event;
use Pwm\SFlow\State;
use Pwm\SFlow\Transition;
use Pwm\SFlow\ShoppingCart\Fixture\Event\Checkout;
use Pwm\SFlow\ShoppingCart\Fixture\State\HasItems;
use Pwm\SFlow\ShoppingCart\Fixture\State\NoCard;

// HasItems -> Checkout -> NoCard
final class DoCheckout implements Transition
{
    public function __invoke(State $state, Event $event): State
    {
        /**
         * @var HasItems $state
         * @var Checkout $event
         */
        return self::transition($state, $event);
    }

    private static function transition(HasItems $state, Checkout $event): NoCard
    {
        return new NoCard($state->getItems());
    }
}
