<?php

declare(strict_types=1);

namespace App\Infrastructure\Converter;

use Ecotone\Messaging\Attribute\Converter;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UuidConverter
{
    #[Converter]
    public function to(string $uuid): UuidInterface
    {
        return Uuid::fromString($uuid);
    }

    #[Converter]
    public function from(UuidInterface $uuid): string
    {
        return $uuid->toString();
    }
}