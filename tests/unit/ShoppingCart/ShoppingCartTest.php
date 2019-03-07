<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart;

use PHPUnit\Framework\TestCase;
use Pwm\SFlow\Arrow;
use Pwm\SFlow\Event as EventType;
use Pwm\SFlow\Events;
use Pwm\SFlow\ShoppingCart\Fixture\Card;
use Pwm\SFlow\ShoppingCart\Fixture\Event;
use Pwm\SFlow\ShoppingCart\Fixture\Item;
use Pwm\SFlow\ShoppingCart\Fixture\State;
use Pwm\SFlow\ShoppingCart\Fixture\Transition;
use Pwm\SFlow\FSM;
use Pwm\SFlow\Graph;

/**
 * The shopping cart example below was borrowed from:
 *   https://wickstrom.tech/finite-state-machines/2017/11/10/finite-state-machines-part-1-modeling-with-haskell.html
 */
final class ShoppingCartTest extends TestCase
{
    /**
     * @test
     */
    public function it_simulates_a_shopping_cart(): void
    {
        // The list of state names to identify vertices in the graph
        $stateNames = [
            State\NoItems::name(),
            State\HasItems::name(),
            State\NoCard::name(),
            State\CardSelected::name(),
            State\CardConfirmed::name(),
            State\OrderPlaced::name(),
        ];

        // The list of arrows between states, labelled by event names, encapsulating transition functions.
        $arrows = [
            (new Arrow(Event\Select::name()))->from(State\NoItems::name())->via(new Transition\AddFirstItem),
            (new Arrow(Event\Select::name()))->from(State\HasItems::name())->via(new Transition\AddItem),
            (new Arrow(Event\Checkout::name()))->from(State\HasItems::name())->via(new Transition\DoCheckout),
            (new Arrow(Event\SelectCard::name()))->from(State\NoCard::name())->via(new Transition\DoSelectCard),
            (new Arrow(Event\Confirm::name()))->from(State\CardSelected::name())->via(new Transition\ConfirmCard),
            (new Arrow(Event\PlaceOrder::name()))->from(State\CardConfirmed::name())->via(new Transition\DoPlaceOrder),
            (new Arrow(Event\Cancel::name()))->from(State\NoCard::name())->via(new Transition\DoCancel),
            (new Arrow(Event\Cancel::name()))->from(State\CardSelected::name())->via(new Transition\DoCancel),
            (new Arrow(Event\Cancel::name()))->from(State\CardConfirmed::name())->via(new Transition\DoCancel),
        ];

        // Build the graph from the above building blocks and build the FSM using the graph.
        $fsm = new FSM((new Graph(...$stateNames))->drawArrows(...$arrows));

        // Run a shopping simulation
        $transitionOp = $fsm->run(
            new State\NoItems(),
            new Events(
                new Event\Select(new Item('foo')),
                new Event\Select(new Item('bar')),
                new Event\Select(new Item('baz')),
                new Event\Checkout(),
                new Event\SelectCard(new Card('Visa', '1234567812345678')),
                new Event\Confirm(),
                new Event\PlaceOrder()
            )
        );

        // The simulation was successful, the final state is OrderPlaced and the last event was PlaceOrder
        self::assertTrue($transitionOp->isSuccess());
        self::assertInstanceOf(State\OrderPlaced::class, $transitionOp->getState());
        self::assertInstanceOf(Event\PlaceOrder::class, $transitionOp->getLastEvent());

        // The list of expected events match the list from the result of the simulation
        self::assertSame([
            Event\Select::class,
            Event\Select::class,
            Event\Select::class,
            Event\Checkout::class,
            Event\SelectCard::class,
            Event\Confirm::class,
            Event\PlaceOrder::class,
        ], array_map(function (EventType $event): string {
            return $event::name()->unWrap();
        }, $transitionOp->getEvents()->toList()));
    }
}
