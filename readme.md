# S-Flow

[![Build Status](https://travis-ci.org/pwm/s-flow.svg?branch=master)](https://travis-ci.org/pwm/s-flow)
[![codecov](https://codecov.io/gh/pwm/s-flow/branch/master/graph/badge.svg)](https://codecov.io/gh/pwm/s-flow)
[![Maintainability](https://api.codeclimate.com/v1/badges/7d68d8bee2ecbcf3277c/maintainability)](https://codeclimate.com/github/pwm/s-flow/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/7d68d8bee2ecbcf3277c/test_coverage)](https://codeclimate.com/github/pwm/s-flow/test_coverage)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

S-Flow is a lightweight library for defining finite state machines (FSM). Once defined the machine can be run by giving it a start state and a sequence of events to derive some end state. One of the main design goals of S-Flow was to be able to define FSMs declaratively as a single top level definition. This makes the structure of the underlying graph clear and explicit which in turn helps with understanding and maintenance. S-Flow can be used for many things, eg. to define workflows or to build event sourced systems.

## Table of Contents

* [Why](#why)
* [Requirements](#requirements)
* [Installation](#installation)
* [Usage](#usage)
* [How it works](#how-it-works)
* [Tests](#tests)
* [Todo](#todo)
* [Changelog](#changelog)
* [Licence](#licence)

## Why

If you ever named a variable, object property or database field *"status"* or *"state"* then read on...

#### Claim #1:

Much grief in software development arises from our inability to control state.

#### Evidence:

Q: What do we do when our code breaks?

A: We debug it.

Q: What does debugging mean?

A: Observing our program's internal state trying to figure out where it went wrong.

#### Claim #2:

If we could better control state in our programs we would have less bugs and as a result we would spend less time debugging.

S-Flow can help controlling state by making it easy to build state machines.

## Requirements

PHP 7.2+

## Installation

    $ composer require pwm/s-flow

## Usage

There is a fully worked example under [tests/unit/ShoppingCart](tests/unit/ShoppingCart) that simulates the process of purchasing items from an imaginary shop. Below is the definition of the FSM from it:

```php
// S, E and T are short for State, Event and Transition

// A list of state names that identify the states
$stateNames = [
    S\NoItems::name(),
    S\HasItems::name(),
    S\NoCard::name(),
    S\CardSelected::name(),
    S\CardConfirmed::name(),
    S\OrderPlaced::name(),
];

// A list of arrows labelled by event names
// An arrow goes from a start state via a transition to an end state
$arrows = [
    (new Arrow(E\Select::name()))->from(S\NoItems::name())->via(new T\AddFirstItem),
    (new Arrow(E\Select::name()))->from(S\HasItems::name())->via(new T\AddItem),
    (new Arrow(E\Checkout::name()))->from(S\HasItems::name())->via(new T\DoCheckout),
    (new Arrow(E\SelectCard::name()))->from(S\NoCard::name())->via(new T\DoSelectCard),
    (new Arrow(E\Confirm::name()))->from(S\CardSelected::name())->via(new T\ConfirmCard),
    (new Arrow(E\PlaceOrder::name()))->from(S\CardConfirmed::name())->via(new T\DoPlaceOrder),
    (new Arrow(E\Cancel::name()))->from(S\NoCard::name())->via(new T\DoCancel),
    (new Arrow(E\Cancel::name()))->from(S\CardSelected::name())->via(new T\DoCancel),
    (new Arrow(E\Cancel::name()))->from(S\CardConfirmed::name())->via(new T\DoCancel),
];

// Build a graph from the above
$graph = (new Graph(...$stateNames))->drawArrows(...$arrows);

// Build an FSM using the graph
$shoppingCartFSM = new FSM($graph);

// Run a simulation of purchasing 3 items
$result = $shoppingCartFSM->run(
    new S\NoItems,
    new Events(
        new E\Select(new Item('foo')),
        new E\Select(new Item('bar')),
        new E\Select(new Item('baz')),
        new E\Checkout,
        new E\SelectCard(new Card('Visa', '1234567812345678')),
        new E\Confirm,
        new E\PlaceOrder
    )
);

// Observe the results
assert($result->isSuccess() === true);
assert($result->getState() instanceof S\OrderPlaced);
assert($result->getLastEvent() instanceof E\PlaceOrder);
```
 
## How it works

A state machine is defined as a directed graph. Vertices of this graph are called states and arrows between them are called transitions. Transitions are labelled so that they can be identified. We call those labels events.

Running the machine, ie. deriving an end state given a start state and a sequence of events, means walking the graph from the start state via a sequence of transitions leading to the desired end state. In the end we either reach it or stop when there is no way forward.

Transitions, acting as the arrows of the graph, are functions of type `(State, Event) -> State`. They are uniquely identified by a `(StateName, EventName)` pair, ie. given a state name and an event name (which is the label of the arrow) we can get the corresponding transition function, if it exists. The the absence of the transition function automatically results in a failed transition, keeping the current state.

Success and failure is captured using the `TransitionOp` type. It also keeps track of the current state as well as the sequence of events leading up to it.

## Tests

	$ composer phpunit
	$ composer phpcs
	$ composer phpstan
	$ composer psalm
	$ composer infection

## Todo

Once return type covariance lands in PHP ([as part of this RFC](https://wiki.php.net/rfc/covariant-returns-and-contravariant-parameters)) we will be able to specify the return type of `__invoke` in `Transition` implementations. Currently it's best to use docblock type hints as per the shopping cart example.

## Changelog

[Click here](changelog.md)

## Licence

[MIT](LICENSE)
