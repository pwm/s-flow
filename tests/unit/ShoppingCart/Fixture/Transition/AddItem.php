<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\Transition;

use Pwm\SFlow\Event;
use Pwm\SFlow\State;
use Pwm\SFlow\Transition;
use Pwm\SFlow\ShoppingCart\Fixture\Event\Select;
use Pwm\SFlow\ShoppingCart\Fixture\Items;
use Pwm\SFlow\ShoppingCart\Fixture\State\HasItems;

// HasItems -> Select -> HasItems
final class AddItem implements Transition
{
    public function __invoke(State $state, Event $event): State
    {
        /**
         * @var HasItems $state
         * @var Select $event
         */
        return $this->transition($state, $event);
    }

    public function transition(HasItems $state, Select $event): HasItems
    {
        return new HasItems(new Items($event->getItem(), ...$state->getItems()));
    }
}
