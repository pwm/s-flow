<?php
declare(strict_types=1);

namespace Pwm\SFlow\Name;

final class EventName
{
    /** @var string */
    private $eventName;

    public function __construct(string $eventName)
    {
        $this->eventName = $eventName;
    }

    public function unWrap(): string
    {
        return $this->eventName;
    }
}
