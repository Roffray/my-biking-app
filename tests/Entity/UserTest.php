<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_userSettersAndGetters(): void
    {
        $user = (new User())
            ->setEmail('test@mybikingapp.com')
            ->setRoles(['ROLE_USER'])
            ->setName('test')
            ->setPassword('password')
            ->setIsActive(true)
        ;

        self::assertNull($user->getId());
        self::assertEquals('test@mybikingapp.com', $user->getEmail());
        self::assertEquals('test@mybikingapp.com', $user->getUsername());
        self::assertEquals('test', $user->getName());
        self::assertEquals('password', $user->getPassword());
        self::assertEquals(['ROLE_USER'], $user->getRoles());
        self::assertTrue($user->getIsActive());
    }

    public function test_userNoSpecifiedRoles(): void
    {
        $user = new User();

        self::assertEquals(['ROLE_USER'], $user->getRoles());
    }
}
