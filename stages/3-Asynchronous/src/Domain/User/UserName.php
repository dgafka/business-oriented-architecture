<?php

declare(strict_types=1);

namespace App\Domain\User;

final class UserName
{
    private function __construct(
        private readonly string $name,
    ) {
        if (strlen($this->name) < 3) {
            throw new \InvalidArgumentException("Name is too short");
        }
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function toString(): string
    {
        return $this->name;
    }
}