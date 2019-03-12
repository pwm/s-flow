# S-Flow

[![Build Status](https://travis-ci.org/pwm/s-flow.svg?branch=master)](https://travis-ci.org/pwm/s-flow)
[![codecov](https://codecov.io/gh/pwm/s-flow/branch/master/graph/badge.svg)](https://codecov.io/gh/pwm/s-flow)
[![Maintainability](https://api.codeclimate.com/v1/badges/7d68d8bee2ecbcf3277c/maintainability)](https://codeclimate.com/github/pwm/s-flow/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/7d68d8bee2ecbcf3277c/test_coverage)](https://codeclimate.com/github/pwm/s-flow/test_coverage)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

S-Flow is a lightweight library for defining finite state machines (FSM). Once defined the machine can be run with a start state and a sequence of events to derive some end state. One of the main design goals of S-Flow was to be able to define FSMs declaratively as a single top level definition. This makes the structure of the underlying graph explicit which greatly helps with understanding and maintenance. FSMs have a wide variety of usage, for example they can be used to define workflows.

## Table of Contents

* [Why](#why)
* [Requirements](#requirements)
* [Installation](#installation)
* [Usage](#usage)
* [How it works](#how-it-works)
* [Tests](#tests)
* [Changelog](#changelog)
* [Licence](#licence)

## Why

Have you ever named a variable, object property or database field *"status"* or *"state"*? If yes then read on...

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

There is a fully worked example under `tests/ShoppingCart` that simulates the process of purchasing items from an imaginary shop. Below is the top level definition of the FSM. For the rest of the code please check `tests/ShoppingCart`.

```php
// A list of state names that identify the states
$stateNames = [
    NoItems::name(),
    HasItems::name(),
    NoCard::name(),
    CardSelected::name(),
    CardConfirmed::name(),
    OrderPlaced::name(),
];

// A list of arrows labelled by event names
// An arrow goes from a start state via a transition to an end state
$arrows = [
    (new Arrow(Select::name()))->from(NoItems::name())->via(new AddFirstItem),
    (new Arrow(Select::name()))->from(HasItems::name())->via(new AddItem),
    (new Arrow(Checkout::name()))->from(HasItems::name())->via(new DoCheckout),
    (new Arrow(SelectCard::name()))->from(NoCard::name())->via(new DoSelectCard),
    (new Arrow(Confirm::name()))->from(CardSelected::name())->via(new ConfirmCard),
    (new Arrow(PlaceOrder::name()))->from(CardConfirmed::name())->via(new DoPlaceOrder),
    (new Arrow(Cancel::name()))->from(NoCard::name())->via(new DoCancel),
    (new Arrow(Cancel::name()))->from(CardSelected::name())->via(new DoCancel),
    (new Arrow(Cancel::name()))->from(CardConfirmed::name())->via(new DoCancel),
];

// Build a graph from the above state names and arrows
$graph = (new Graph(...$stateNames))->drawArrows(...$arrows);

// Build the FSM using the graph and run a shopping simulation
$transitionOp = (new FSM($graph))->run(
    new NoItems(),
    new Events(
        new Select(new Item('foo')),
        new Select(new Item('bar')),
        new Select(new Item('baz')),
        new Checkout(),
        new SelectCard(new Card('Visa', '1234567812345678')),
        new Confirm(),
        new PlaceOrder()
    )
);

// Observe the results
assert($transitionOp->isSuccess() === true);
assert($transitionOp->getState() instanceof OrderPlaced);
assert($transitionOp->getLastEvent() instanceof PlaceOrder);
```
 
## How it works

A state machine is defined as a directed graph. Vertices of this graph are called states and arrows between them are called transitions. Transitions are labelled so that they can be identified. We call those labels events.

Running the machine, ie. deriving an end state given a start state and a sequence of events, means walking the graph from the start state via a sequence of transitions leading to the desired end state. In the end we either reach it or stop when there is no way forward.

Transitions, acting as the arrows of the graph, are functions of type `State -> Event -> State`. They are uniquely identified by a `(State, Event)` pair, ie. given a state and an event (which is the label of the arrow) we can get the corresponding transition function, if it exists. The the absence of a transition function automatically results in a failed transition.

Success and failure is captured using the `TransitionOp` type. It also keeps track of the current state as well as the sequence of events leading up to it.

## Tests

	$ composer phpunit
	$ composer phpcs
	$ composer phpstan
	$ composer psalm
	$ composer infection

## Changelog

[Click here](changelog.md)

## Licence

[MIT](LICENSE)
