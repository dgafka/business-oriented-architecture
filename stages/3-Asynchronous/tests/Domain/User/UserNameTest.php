<?php

declare(strict_types=1);

namespace Tests\App\Domain\User\UserName;

use App\Domain\User\UserName;
use PHPUnit\Framework\TestCase;

final class UserNameTest extends TestCase
{
    public function test_it_should_create_user_name(): void
    {
        $userName = UserName::create('John');

        $this->assertEquals('John', $userName->toString());
    }

    public function test_throwing_exception_when_name_is_too_short(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        UserName::create('Jo');
    }
}