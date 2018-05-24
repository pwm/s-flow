<?php
declare(strict_types=1);

namespace Pwm\SFlow;

final class StateOp
{
    /** @var string */
    private $state;
    /** @var string */
    private $success;

    public static function success(string $state): self
    {
        return new self(true, $state);
    }

    public static function failure(string $state): self
    {
        return new self(false, $state);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getState(): string
    {
        return $this->state;
    }

    private function __construct(bool $success, string $state)
    {
        $this->success = $success;
        $this->state = $state;
    }
}
