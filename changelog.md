# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [3.0.0] - 2018-09-28
### Changed
  * `array` type hints have been replaced with `string ...` for more explicit control. This is a breaking change for clients!
  * The `StateOp` object now has a `->getLastEvent()` method 

## [2.1.0] - 2018-09-28
### Changed
  * The `StateOp` object now has the events applied 

## [2.0.0] - 2018-05-24
### Changed
  * `->deriveState()` now returns a `StateOp` object to indicate success/failure 

## [1.0.0] - 2018-05-18
### Added
  * Initial release
