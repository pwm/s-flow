<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\Transition;

use Pwm\SFlow\Event;
use Pwm\SFlow\State;
use Pwm\SFlow\Transition;
use Pwm\SFlow\ShoppingCart\Fixture\Event\Cancel;
use Pwm\SFlow\ShoppingCart\Fixture\State\CardConfirmed;
use Pwm\SFlow\ShoppingCart\Fixture\State\CardSelected;
use Pwm\SFlow\ShoppingCart\Fixture\State\HasItems;
use Pwm\SFlow\ShoppingCart\Fixture\State\NoCard;

// NoCard | CardSelected | CardConfirmed -> Cancel -> HasItems
// state -> Cancel -> state
final class DoCancel implements Transition
{
    public function __invoke(State $state, Event $event): State
    {
        /**
         * @var State $state
         * @var Cancel $event
         */
        return self::transition($state, $event);

    }

    private static function transition(State $state, Cancel $event): State
    {
        if ($state instanceof NoCard ||
            $state instanceof CardSelected ||
            $state instanceof CardConfirmed) {
            return new HasItems($state->getItems());
        }
        return $state;
    }
}
