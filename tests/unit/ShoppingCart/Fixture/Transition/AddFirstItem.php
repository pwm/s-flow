<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture\Transition;

use Pwm\SFlow\Event;
use Pwm\SFlow\State;
use Pwm\SFlow\Transition;
use Pwm\SFlow\ShoppingCart\Fixture\Event\Select;
use Pwm\SFlow\ShoppingCart\Fixture\Items;
use Pwm\SFlow\ShoppingCart\Fixture\State\HasItems;
use Pwm\SFlow\ShoppingCart\Fixture\State\NoItems;

// NoItems -> Select -> HasItems
final class AddFirstItem implements Transition
{
    public function __invoke(State $state, Event $event): State
    {
        /**
         * @var NoItems $state
         * @var Select $event
         */
        return self::transition($state, $event);
    }

    private static function transition(NoItems $state, Select $event): HasItems
    {
        return new HasItems(new Items($event->getItem()));
    }
}
