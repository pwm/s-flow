<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use PHPUnit\Framework\TestCase;
use Pwm\SFlow\Fixture\NoopTransition;
use Pwm\SFlow\Fixture\TestEvent1;
use Pwm\SFlow\Fixture\TestEvent2;
use Pwm\SFlow\Fixture\TestState1;
use Pwm\SFlow\Fixture\TestState2;
use Pwm\SFlow\Fixture\TestTransition12;
use Pwm\SFlow\Fixture\TestTransition21;

final class GraphTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_as_a_null_graph(): void
    {
        $graph = new Graph();

        self::assertInstanceOf(Graph::class, $graph);
    }

    /**
     * @test
     */
    public function it_creates_as_an_arrowless_graph(): void
    {
        $graph = new Graph(TestState1::name(), TestState2::name());

        self::assertInstanceOf(Graph::class, $graph);
    }

    /**
     * @test
     * @expectedException \Pwm\SFlow\Exception\DuplicateState
     */
    public function it_throws_on_duplicate_state_names(): void
    {
        new Graph(TestState1::name(), TestState1::name());
    }

    /**
     * @test
     */
    public function arrows_can_be_drawn(): void
    {
        $stateNames = [
            TestState1::name(),
            TestState2::name(),
        ];

        $arrows = [
            (new Arrow(TestEvent1::name()))->from(TestState1::name())->via(new TestTransition12),
            (new Arrow(TestEvent2::name()))->from(TestState2::name())->via(new TestTransition21),
        ];

        $graph = (new Graph(...$stateNames))->drawArrows(...$arrows);

        self::assertInstanceOf(Graph::class, $graph);
        self::assertInstanceOf(TestTransition12::class, $graph->getTransition(new TestState1, new TestEvent1));
        self::assertInstanceOf(TestTransition21::class, $graph->getTransition(new TestState2, new TestEvent2));
        self::assertNull($graph->getTransition(new TestState1, new TestEvent2));
        self::assertNull($graph->getTransition(new TestState2, new TestEvent1));
    }

    /**
     * @test
     */
    public function arrow_can_be_drawn(): void
    {
        $stateNames = [
            TestState1::name(),
            TestState2::name(),
        ];

        $arrow = (new Arrow(TestEvent1::name()))->from(TestState1::name())->via(new NoopTransition);

        $graph = (new Graph(...$stateNames))->drawArrow($arrow);

        self::assertInstanceOf(Graph::class, $graph);
        self::assertInstanceOf(NoopTransition::class, $graph->getTransition(new TestState1, new TestEvent1));
    }

    /**
     * @test
     */
    public function arrows_can_be_added(): void
    {
        $stateNames = [
            TestState1::name(),
            TestState2::name(),
        ];

        $drawnArrows = [
            new DrawnArrow(TestEvent1::name(), TestState1::name(), new TestTransition12),
            new DrawnArrow(TestEvent2::name(), TestState2::name(), new TestTransition21),
        ];

        $graph = (new Graph(...$stateNames))->addArrows(...$drawnArrows);

        self::assertInstanceOf(Graph::class, $graph);
        self::assertInstanceOf(TestTransition12::class, $graph->getTransition(new TestState1, new TestEvent1));
        self::assertInstanceOf(TestTransition21::class, $graph->getTransition(new TestState2, new TestEvent2));
        self::assertNull($graph->getTransition(new TestState1, new TestEvent2));
        self::assertNull($graph->getTransition(new TestState2, new TestEvent1));
    }

    /**
     * @test
     */
    public function arrow_can_be_added(): void
    {
        $stateNames = [
            TestState1::name(),
            TestState2::name(),
        ];

        $arrow = new DrawnArrow(TestEvent1::name(), TestState1::name(), new NoopTransition);

        $graph = (new Graph(...$stateNames))->addArrow($arrow);

        self::assertInstanceOf(Graph::class, $graph);
        self::assertInstanceOf(NoopTransition::class, $graph->getTransition(new TestState1, new TestEvent1));
    }

    /**
     * @test
     * @expectedException \Pwm\SFlow\Exception\MissingState
     */
    public function cannot_add_arrow_from_an_unknown_state(): void
    {
        (new Graph(TestState1::name()))
            ->addArrow(new DrawnArrow(TestEvent1::name(), TestState2::name(), new NoopTransition));
    }

    /**
     * @test
     * @expectedException \Pwm\SFlow\Exception\DuplicateEvent
     */
    public function events_must_be_unique_coming_out_of_a_state(): void
    {
        (new Graph(TestState1::name(), TestState2::name()))
            ->addArrow(new DrawnArrow(TestEvent1::name(), TestState1::name(), new NoopTransition))
            ->addArrow(new DrawnArrow(TestEvent1::name(), TestState1::name(), new NoopTransition));
    }
}
