<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\Transition;

use Pwm\SFlow\Event;
use Pwm\SFlow\State;
use Pwm\SFlow\Transition;
use Pwm\SFlow\ShoppingCart\Fixture\Event\Confirm;
use Pwm\SFlow\ShoppingCart\Fixture\State\CardConfirmed;
use Pwm\SFlow\ShoppingCart\Fixture\State\CardSelected;

// CardSelected -> Confirm -> CardConfirmed
final class ConfirmCard implements Transition
{
    public function __invoke(State $state, Event $event): State
    {
        /**
         * @var CardSelected $state
         * @var Confirm $event
         */
        return $this->transition($state, $event);
    }

    public function transition(CardSelected $state, Confirm $event): CardConfirmed
    {
        return new CardConfirmed($state->getItems(), $state->getCard());
    }
}
