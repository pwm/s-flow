# S-Flow

[![Build Status](https://travis-ci.org/pwm/s-flow.svg?branch=master)](https://travis-ci.org/pwm/s-flow)
[![codecov](https://codecov.io/gh/pwm/s-flow/branch/master/graph/badge.svg)](https://codecov.io/gh/pwm/s-flow)
[![Maintainability](https://api.codeclimate.com/v1/badges/7d68d8bee2ecbcf3277c/maintainability)](https://codeclimate.com/github/pwm/s-flow/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/7d68d8bee2ecbcf3277c/test_coverage)](https://codeclimate.com/github/pwm/s-flow/test_coverage)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

S-Flow is a small library for defining state machines. Once defined we can run them to derive some final state given a start state and a list of events. Transitions between states can be made conditional by supplying functions to them.

## Table of Contents

* [Requirements](#requirements)
* [Installation](#installation)
* [Usage](#usage)
* [How it works](#how-it-works)
* [Tests](#tests)
* [Changelog](#changelog)
* [Licence](#licence)

## Requirements

PHP 7.1+

## Installation

    $ composer require pwm/s-flow

## Usage

TBD
 
## How it works

TBD

## Tests

	$ vendor/bin/phpunit
	$ composer phpcs
	$ composer phpstan

## Changelog

[Click here](changelog.md)

## Licence

[MIT](LICENSE)
