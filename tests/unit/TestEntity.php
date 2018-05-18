<?php
declare(strict_types=1);

namespace Pwm\SFlow;

class TestEntity
{
    /** @var string */
    private $required;
    /** @var string[] */
    private $events;
    /** @var null|string */
    private $optional;
    /** @var string */
    private $state;

    public function __construct(
        string $required,
        array $events = [],
        string $optional = null
    ) {
        $this->required = $required;
        $this->events = $events;
        $this->optional = $optional;
    }

    public function getState(): string
    {
        return $this->state ?? $this->deriveState('S1', $this->events);
    }

    private function deriveState(string $startState, array $events): string
    {
        $optionalIsSet = function (): bool {
            return $this->optional !== null;
        };

        $fsm = (new FSM(['S1', 'S2', 'S3']))
            ->addTransition((new Transition('E1'))->from('S1')->to('S2'))
            ->addTransition((new Transition('E2'))->from('S2')->given($optionalIsSet)->to('S3'));

        return $fsm->deriveState($startState, $events);
    }
}
