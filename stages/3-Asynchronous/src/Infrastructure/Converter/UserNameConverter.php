<?php

declare(strict_types=1);

namespace App\Infrastructure\Converter;

use App\Domain\User\UserName;
use Ecotone\Messaging\Attribute\Converter;

final class UserNameConverter
{
    #[Converter]
    public function to(string $name): UserName
    {
        return UserName::create($name);
    }

    #[Converter]
    public function from(UserName $name): string
    {
        return $name->toString();
    }
}