<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\Transition;

use Pwm\SFlow\Event;
use Pwm\SFlow\State;
use Pwm\SFlow\Transition;
use Pwm\SFlow\ShoppingCart\Fixture\Event\SelectCard;
use Pwm\SFlow\ShoppingCart\Fixture\State\CardSelected;
use Pwm\SFlow\ShoppingCart\Fixture\State\NoCard;

// NoCard -> SelectCard -> CardSelected
final class DoSelectCard implements Transition
{
    public function __invoke(State $state, Event $event): State
    {
        /**
         * @var NoCard $state
         * @var SelectCard $event
         */
        return $this->transition($state, $event);
    }

    public function transition(NoCard $state, SelectCard $event): CardSelected
    {
        return new CardSelected($state->getItems(), $event->getCard());
    }
}
