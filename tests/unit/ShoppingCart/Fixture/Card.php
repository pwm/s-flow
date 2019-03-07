<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture;

final class Card
{
    /** @var string */
    private $type;
    /** @var string */
    private $number;

    public function __construct(string $type, string $number)
    {
        $this->type = $type;
        $this->number = $number;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getNumber(): string
    {
        return $this->number;
    }
}
