<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\Command\ChangeUserName;
use App\Domain\User\Command\RegisterUser;
use App\Domain\User\Event\UserWasRegistered;
use Ecotone\Modelling\Attribute\Aggregate;
use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\QueryHandler;
use Ecotone\Modelling\WithAggregateEvents;
use Ramsey\Uuid\UuidInterface;

#[Aggregate]
final class User
{
    use WithAggregateEvents;

    private function __construct(
        #[AggregateIdentifier] private readonly UuidInterface $userId,
        private UserName               $name,
        private bool $isBlocked = false
    )
    {
        $this->recordThat(new UserWasRegistered($this->userId, $this->name));
    }

    #[CommandHandler]
    public static function create(RegisterUser $command): self
    {
        return new self($command->userId, $command->name);
    }

    #[CommandHandler]
    public function changeName(ChangeUserName $command): void
    {
        if ($this->isBlocked) {
            throw new \RuntimeException("User is blocked");
        }

        $this->name = $command->name;
    }

    #[CommandHandler("user.block")]
    public function block(): void
    {
        $this->isBlocked = true;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    #[QueryHandler("user.getName")]
    public function getName(): UserName
    {
        return $this->name;
    }

    #[QueryHandler("user.isBlocked")]
    public function isBlocker(): bool
    {
        return $this->isBlocked;
    }
}