<?php
declare(strict_types=1);

namespace Pwm\SFlow\Name;

final class StateName
{
    /** @var string */
    private $stateName;

    public function __construct(string $stateName)
    {
        $this->stateName = $stateName;
    }

    public function unWrap(): string
    {
        return $this->stateName;
    }
}
