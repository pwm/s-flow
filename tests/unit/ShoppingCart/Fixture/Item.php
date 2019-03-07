<?php
declare(strict_types=1);

namespace Pwm\SFlow\ShoppingCart\Fixture;

final class Item
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
