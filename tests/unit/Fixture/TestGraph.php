<?php
declare(strict_types=1);

namespace Pwm\SFlow\Fixture;

use Pwm\SFlow\Arrow;
use Pwm\SFlow\Graph;

final class TestGraph
{
    public static function create(): Graph
    {
        $stateNames = [
            TestState1::name(),
            TestState2::name(),
        ];

        $arrows = [
            (new Arrow(TestEvent1::name()))->from(TestState1::name())->via(new TestTransition12),
            (new Arrow(TestEvent2::name()))->from(TestState2::name())->via(new TestTransition21),
        ];

        return (new Graph(...$stateNames))->drawArrows(...$arrows);
    }
}
