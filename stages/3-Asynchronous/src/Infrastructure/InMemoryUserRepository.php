<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Ecotone\Modelling\Attribute\Repository;
use Ecotone\Modelling\StandardRepository;
use Ramsey\Uuid\UuidInterface;

/** Integration with Ecotone, so aggregate can be found automatically */
#[Repository]
final class InMemoryUserRepository implements StandardRepository
{
    /** @var User[] */
    private array $users = [];

    public function canHandle(string $aggregateClassName): bool
    {
        return $aggregateClassName === User::class;
    }

    public function findBy(string $aggregateClassName, array $identifiers): ?object
    {
        $userId = array_pop($identifiers);

        return $this->users[$userId] ?? null;
    }

    public function save(array $identifiers, object $aggregate, array $metadata, ?int $versionBeforeHandling): void
    {
        $userId = array_pop($identifiers);

        $this->users[$userId] = $aggregate;
    }
}