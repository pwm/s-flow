<?php
declare(strict_types=1);

namespace Pwm\SFlow;

use Closure;
use PHPUnit\Framework\TestCase;
use Pwm\SFlow\Exception\IncompleteTransition;
use Throwable;

final class TransitionTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates(): void
    {
        $transition = new Transition(
            'someEvent',
            'fromState',
            'toState',
            function ($x) { return $x; }
        );

        self::assertInstanceOf(Transition::class, $transition);
        self::assertSame('someEvent', $transition->getEvent());
        self::assertSame('fromState', $transition->getFrom());
        self::assertSame('toState', $transition->getTo());
        self::assertInstanceOf(Closure::class, $transition->getCondition());
    }

    /**
     * @test
     */
    public function it_builds(): void
    {
        $transition = (new Transition('someEvent'))
            ->from('fromState')
            ->to('toState')
            ->given(function ($x) { return $x; });

        self::assertInstanceOf(Transition::class, $transition);
        self::assertSame('someEvent', $transition->getEvent());
        self::assertSame('fromState', $transition->getFrom());
        self::assertSame('toState', $transition->getTo());
        self::assertInstanceOf(Closure::class, $transition->getCondition());
    }

    /**
     * @test
     */
    public function transition_condition_is_optional(): void
    {
        $transition = (new Transition('someEvent'))
            ->from('fromState')
            ->to('toState');

        self::assertNull($transition->getCondition());
    }

    /**
     * @test
     */
    public function throws_on_accessing_from_to_and_condition_of_an_incomplete_transition(): void
    {
        $tryToAccess = function (Transition $incompleteTransition) {
            try {
                $incompleteTransition->getFrom();
            } catch (Throwable $e) {
                self::assertInstanceOf(IncompleteTransition::class, $e);
            }
            try {
                $incompleteTransition->getTo();
            } catch (Throwable $e) {
                self::assertInstanceOf(IncompleteTransition::class, $e);
            }
            try {
                $incompleteTransition->getCondition();
            } catch (Throwable $e) {
                self::assertInstanceOf(IncompleteTransition::class, $e);
            }
        };

        $incompleteTransitions = [
            new Transition('someEvent'), // no from() or to()
            (new Transition('someEvent'))->from('fromState'), // no to()
            (new Transition('someEvent'))->to('toState'), // no from()
        ];

        foreach ($incompleteTransitions as $incompleteTransition) {
            $tryToAccess($incompleteTransition);
        }
    }
}
