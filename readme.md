# S-Flow

[![Build Status](https://travis-ci.org/pwm/s-flow.svg?branch=master)](https://travis-ci.org/pwm/s-flow)
[![codecov](https://codecov.io/gh/pwm/s-flow/branch/master/graph/badge.svg)](https://codecov.io/gh/pwm/s-flow)
[![Maintainability](https://api.codeclimate.com/v1/badges/7d68d8bee2ecbcf3277c/maintainability)](https://codeclimate.com/github/pwm/s-flow/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/7d68d8bee2ecbcf3277c/test_coverage)](https://codeclimate.com/github/pwm/s-flow/test_coverage)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

S-Flow is a small library for defining finite state machines (FSM). Once defined we can run them to derive some final state given a start state and a list of events. Transitions between states can be made conditional by supplying predicate functions to them.

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

What do we do when our code breaks? We debug it. What does debugging mean? Observing internal state and trying to figure out where it goes wrong.

#### Claim #2:

If we could better control state in our programs we would have less bugs and as a result we would spend less time debugging.

S-Flow can help controlling state by making it easy to build state machines.

## Requirements

PHP 7.1+

## Installation

    $ composer require pwm/s-flow

## Usage

Let's go through a worked example of handling traffic lights.

```php
class TrafficLight {
    /** @var string */
    private $colour;

    public function __construct() {
        $this->colour = 'Red';
    }

    public function change(string $colour): void {
        $this->colour = $colour;
    }

    public function getColour(): string {
        return $this->colour;
    }
}
```

There are 2 problems with our `TrafficLight` class. The obvious first issue is:

```php
$trafficLight = new TrafficLight(); // Red
$trafficLight->change('Black'); // Excuse me?
```

This is bad but easily fixable by restricting the set of possible states, eg. introducing a `Colours` enum class instead of an arbitrary `string`. We won't show that here, we'll just assume that from now on all states we use are from the set of valid states, namely Red, Yellow and Green.

There is however another issue, which is less clear at first:

```php
$trafficLight = new TrafficLight(); // Red
$trafficLight->change('Green'); // Green
$trafficLight->change('Red'); // Red, but what happened to Yellow?
```

Nothing says that we can't transition from one state to any other (from the set of allowed states), hence we can easily create the above situation, causing chaos on the road.

Let's go ahead and fix it using S-Flow:

```php
class TrafficLight {
    /** @var string */
    private $colour;
    /** @var FSM */
    private $fsm;

    public function __construct() {
        $this->setupFSM();
        $this->colour = 'Red';
    }

    public function change(string ...$events): void {
        $this->colour = $this->fsm->deriveState($this->colour, $events);
    }

    public function getColour(): string {
        return $this->colour;
    }

    private function setupFSM(): void {
        $this->fsm = (new FSM(['Red', 'Yellow', 'Green']))
            ->addTransition((new Transition('Go'))->from('Red')->to('Green'))
            ->addTransition((new Transition('Slow'))->from('Green')->to('Yellow'))
            ->addTransition((new Transition('Stop'))->from('Yellow')->to('Red'));
    }
}
```

That's quite a mouthful. Let's see what's going on here. We have changed 2 things:

 * We introduced a finite state machine (FSM) to control state transition
 * We can now only change state indirectly, by supplying a list of events

If we look at the FSM it's pretty self-explanatory. It gets a set of allowed states and a set of transitions between them. We define that a traffic light can go from Red to Green (via the "Go" event), from Green to Yellow (via the "Slow" event) and from Yellow to Red (via the "Stop" event) and that's it. It can't go for example from Green to Red as there's no such transition. This is pretty neat as now we can't make a mistake. If we supply an out-of-order event then the state stays the same.

```php
$trafficLight = new TrafficLight(); // Red
$trafficLight->change('Go'); // Green
$trafficLight->change('Stop'); // Nope, still Green...
$trafficLight->change('Slow'); // Yellow
$trafficLight->change('Stop'); // Red
```

Here's a full cycle in one go:

```php
$trafficLight = new TrafficLight(); // Red
$trafficLight->change('Go', 'Slow', 'Stop'); // Red again
```

Finally we can make a transition conditional by supplying a predicate function to it (a predicate is a function that returns true or false).

```php
class TrafficLight {
    /** @var string */
    private $model;
    /** @var string */
    private $colour;
    /** @var FSM */
    private $fsm;

    public function __construct(string $model) {
        $this->model = $model;
        $this->setupFSM();
        $this->colour = 'Red';
    }

    public function change(string ...$events): void {
        $this->colour = $this->fsm->deriveState($this->colour, $events);
    }

    public function getColour(): string {
        return $this->colour;
    }

    private function setupFSM(): void {
        $newModel = function (): bool {
            return $this->model  === 'new';
        };

        $this->fsm = (new FSM(['Red', 'Yellow', 'Green', 'RedYellow']))
            ->addTransition((new Transition('Prepare'))->from('Red')->given($newModel)->to('RedYellow'))
            ->addTransition((new Transition('Go'))->from('Red')->to('Green'))
            ->addTransition((new Transition('Go'))->from('RedYellow')->to('Green'))
            ->addTransition((new Transition('Slow'))->from('Green')->to('Yellow'))
            ->addTransition((new Transition('Stop'))->from('Yellow')->to('Red'));
    }
}
```

Now our `TrafficLight` can handle newer models with extra features. The older models work as before.

```php
$trafficLight = new TrafficLight('old'); // Red
$trafficLight->change('Prepare'); // Still Red, it's an old model
$trafficLight->change('Go'); // Green
```

A newer model got an extra state in which both Red and Yellow are lit, meaning "prepare to go".

```php
$trafficLight = new TrafficLight('new'); // Red
$trafficLight->change('Prepare'); // RedYellow, as it's a new model
$trafficLight->change('Go'); // Green
```
 
## How it works

A state machine is essentially a directed graph, where nodes are states and arrows are transitions between them. Transitions are labelled and we call those labels events. 

Deriving a state means walking the graph from some start state via a set of transitions leading to the destination state, either reaching it or stopping when there's no way forward.

Transitions can be conditional to allow more flexible definitions. A conditional transition is one with a corresponding predicate function. It can only be travelled if its predicate evaluates to true.

## Tests

	$ vendor/bin/phpunit
	$ composer phpcs
	$ composer phpstan

## Changelog

[Click here](changelog.md)

## Licence

[MIT](LICENSE)
